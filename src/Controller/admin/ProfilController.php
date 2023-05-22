<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\admin\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfilController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/admin/profil', name: 'admin.profil.show', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $profilForm = $this->createForm( ProfilFormType::class, $user);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');

            return $this->redirectToRoute('admin.profil.show');
        }

        if($this->getUser() === null) {
            return $this->redirectToRoute('admin.security.connexion');
        }

        $menu_active = 'profil';
        $userInfo = $this->getUser();

        return $this->render('admin/profil/show.html.twig', [
            'menu_active' => $menu_active,
            'profilForm' => $profilForm->createView(),
            'userInfo' => $userInfo,
        ]);
    }
}
