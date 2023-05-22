<?php

namespace App\Controller\admin;

use App\Entity\Projet;
use App\Form\admin\ProjetFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/projet', name: 'admin.projet.')]
class ProjetController extends AbstractController
{
    #[Route('/', name: 'show')]
    public function index(ManagerRegistry $registry): Response
    {
        $projets = $registry->getManager()->getRepository(Projet::class)->createQueryBuilder('p')
            ->select('p.id, p.nom, p.telechargement, p.visuel, pt.nom as type_nom')
            ->join('p.type', 'pt')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

        $menu_active = 'projet';

        return $this->render('admin/projet/show.html.twig', [
            'projets' => $projets,
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $projet = new Projet();

        $projetForm = $this->createForm(ProjetFormType::class, $projet);

        $projetForm->handleRequest($request);

        if($projetForm->isSubmitted() && $projetForm->isValid())
        {
            $entityManager->persist($projet);
            $entityManager->flush();

            $this->addFlash('success', "Le projet a bien été ajouté !");

            return $this->redirectToRoute('admin.projet.show');
        }

        $menu_active = 'projet';

        return $this->render('admin/projet/add.html.twig', [
            'projetForm' => $projetForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Projet $projet, EntityManagerInterface $entityManager, Request $request): Response
    {
        $projetForm = $this->createForm(ProjetFormType::class, $projet);

        $projetForm->handleRequest($request);

        if($projetForm->isSubmitted() && $projetForm->isValid())
        {
            $entityManager->persist($projet);
            $entityManager->flush();

            $this->addFlash('success', "Le projet a bien été modifié !");

            return $this->redirectToRoute('admin.projet.show');
        }

        $menu_active = 'projet';

        return $this->render('admin/projet/edit.html.twig', [
            'projetForm' => $projetForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Projet $projet, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$projet->getId(), $request->get('_token')))
        {
            $entityManager->remove($projet);
            $entityManager->flush();

            $this->addFlash('success', "Le projet a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.projet.show');
    }
}
