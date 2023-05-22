<?php

namespace App\Controller\admin;

use App\Entity\Experience;
use App\Form\admin\ExperienceFormType;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/experience', name: 'admin.experience.')]
class ExperienceController extends AbstractController
{
    private $repository;

    public function __construct(ExperienceRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('/', name: 'show')]
    public function index(): Response
    {
        $experiences = $this->repository->findBy([], ['id' => 'DESC']);

        $menu_active = 'experience';

        return $this->render('admin/experience/show.html.twig', [
            'experiences' => $experiences,
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $experience = new Experience();

        $experienceForm = $this->createForm(ExperienceFormType::class, $experience);

        $experienceForm->handleRequest($request);

        if($experienceForm->isSubmitted() && $experienceForm->isValid())
        {
            $entityManager->persist($experience);
            $entityManager->flush();

            $this->addFlash('success', "L'expérience a bien été ajoutée !");

            return $this->redirectToRoute('admin.experience.show');
        }

        $menu_active = 'experience';

        return $this->render('admin/experience/add.html.twig', [
            'experienceForm' => $experienceForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Experience $experience, EntityManagerInterface $entityManager, Request $request): Response
    {
        $experienceForm = $this->createForm(ExperienceFormType::class, $experience);

        $experienceForm->handleRequest($request);

        if($experienceForm->isSubmitted() && $experienceForm->isValid())
        {
            $entityManager->persist($experience);
            $entityManager->flush();

            $this->addFlash('success', "L'expérience a bien été modifiée !");

            return $this->redirectToRoute('admin.experience.show');
        }

        $menu_active = 'experience';

        return $this->render('admin/experience/edit.html.twig', [
            'experienceForm' => $experienceForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Experience $experience, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$experience->getId(), $request->get('_token')))
        {
            $entityManager->remove($experience);
            $entityManager->flush();

            $this->addFlash('success', "L'expérience a bien été supprimée !");
        }

        return $this->redirectToRoute('admin.experience.show');
    }
}
