<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\admin\FormationFormType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/formation', name: 'admin.formation.')]
class FormationController extends AbstractController
{
    private $repository;

    public function __construct(FormationRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'show')]
    public function index(): Response
    {
        $formations = $this->repository->findBy([], ['id' => 'DESC']);

        $menu_active = 'formation';

        return $this->render('admin/formation/show.html.twig', [
            'formations' => $formations,
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $formation = new Formation();

        $formationForm = $this->createForm(FormationFormType::class, $formation);

        $formationForm->handleRequest($request);

        if($formationForm->isSubmitted() && $formationForm->isValid())
        {
            $entityManager->persist($formation);
            $entityManager->flush();

            $this->addFlash('success', "La formation a bien été ajoutée !");

            return $this->redirectToRoute('admin.formation.show');
        }

        $menu_active = 'formation';

        return $this->render('admin/formation/add.html.twig', [
            'formationForm' => $formationForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Formation $formation, EntityManagerInterface $entityManager, Request $request): Response
    {
        $formationForm = $this->createForm(FormationFormType::class, $formation);

        $formationForm->handleRequest($request);

        if($formationForm->isSubmitted() && $formationForm->isValid())
        {
            $entityManager->persist($formation);
            $entityManager->flush();

            $this->addFlash('success', "La formation a bien été modifiée !");

            return $this->redirectToRoute('admin.formation.show');
        }

        $menu_active = 'formation';

        return $this->render('admin/formation/edit.html.twig', [
            'formationForm' => $formationForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Formation $formation, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$formation->getId(), $request->get('_token')))
        {
            $entityManager->remove($formation);
            $entityManager->flush();

            $this->addFlash('success', "La formation a bien été supprimée !");
        }

        return $this->redirectToRoute('admin.formation.show');
    }
}
