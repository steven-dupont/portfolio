<?php

namespace App\Controller\admin;

use App\Entity\Competence;
use App\Form\admin\CompetenceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/competence', name: 'admin.competence.')]
class CompetenceController extends AbstractController
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getCompetence()
    {
        $entityManager = $this->registry->getManager();
        $competenceRepository = $entityManager->getRepository(Competence::class);
        return $competenceRepository->createQueryBuilder('c')
            ->select('c.id, c.nom, c.framework, ct.nom as type_nom')
            ->join('c.type', 'ct')
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    #[Route('/', name: 'show')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $menu_active = 'competence';

        return $this->render('admin/competence/show.html.twig', [
            'competences' => $this->getCompetence(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $competence = new Competence();

        $competenceForm = $this->createForm(CompetenceFormType::class, $competence);

        $competenceForm->handleRequest($request);

        if($competenceForm->isSubmitted() && $competenceForm->isValid())
        {
            $entityManager->persist($competence);
            $entityManager->flush();

            $this->addFlash('success', 'La compétence a bien été ajoutée !');

            return $this->redirectToRoute('admin.competence.show');
        }

        $menu_active = 'competence';

        return $this->render('admin/competence/add.html.twig', [
            'competenceForm' => $competenceForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function editCompetence(Competence $competence, EntityManagerInterface $entityManager, Request $request): Response
    {
        $competenceForm = $this->createForm(CompetenceFormType::class, $competence);

        $competenceForm->handleRequest($request);

        if($competenceForm->isSubmitted() && $competenceForm->isValid())
        {
            $entityManager->persist($competence);
            $entityManager->flush();

            $this->addFlash('success', 'La compétence a bien été modifiée !');

            return $this->redirectToRoute('admin.competence.show');
        }

        $menu_active = 'competence';

        return $this->render('admin/competence/edit.html.twig', [
            'competenceForm' => $competenceForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function deleteCompetence(Competence $competence, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$competence->getId(), $request->get('_token')))
        {
            $entityManager->remove($competence);
            $entityManager->flush();

            $this->addFlash('success', 'La compétence a bien été supprimée !');
        }

        return $this->redirectToRoute('admin.competence.show');
    }
}
