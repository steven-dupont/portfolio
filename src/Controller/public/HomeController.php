<?php

namespace App\Controller\public;

use App\Entity\Competence;
use App\Entity\Contact;
use App\Entity\Experience;
use App\Entity\Formation;
use App\Entity\Projet;
use App\Entity\ProjetType;
use App\Entity\User;
use App\Form\public\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function utilisateurRepo()
    {
        // Récupération de l'utilisateur avec l'id 1
        $entityManager = $this->registry->getManager();
        $utilisateurRepository = $entityManager->getRepository(User::class);
        return $utilisateurRepository->find(1);
    }

    public function getNaissanceUser()
    {
        // Récupération de la date de naissance
        return utf8_encode(strftime("%e %B %Y", $this->utilisateurRepo()->getNaissance()));
    }
    public function getAgeUser()
    {
        // Récupération de l'age en fonction de la date de naissance
        $dateNaissance = date('d-m-Y', $this->utilisateurRepo()->getNaissance());
        $aujourdhui = date("Y-m-d");
        $diff = date_diff(date_create($dateNaissance), date_create($aujourdhui));
        return $diff->format('%y');
    }

    public function getCompetenceReseau()
    {
        $entityManager = $this->registry->getManager();
        $competenceRepository = $entityManager->getRepository(Competence::class);
        return $competenceRepository->createQueryBuilder('c')
            ->join('c.type', 'ct')
            ->where('ct.id = :type_id')
            ->andWhere('ct.nom = :nom')
            ->setParameter('type_id', 1)
            ->setParameter('nom', 'Réseau')
            ->getQuery()
            ->getResult();
    }

    public function getCompetenceDeveloppement()
    {
        $entityManager = $this->registry->getManager();
        $competenceRepository = $entityManager->getRepository(Competence::class);
        return $competenceRepository->createQueryBuilder('c')
            ->join('c.type', 'ct')
            ->where('ct.id = :type_id')
            ->andWhere('ct.nom = :nom')
            ->setParameter('type_id', 2)
            ->setParameter('nom', 'Développement')
            ->getQuery()
            ->getResult();
    }

    public function getFormation()
    {
        $entityManager = $this->registry->getManager();
        $formationRepository = $entityManager->getRepository(Formation::class);
        return $formationRepository->findBy([], ['id' => 'DESC']);
    }

    public function getExperience()
    {
        $entityManager = $this->registry->getManager();
        $experienceRepository = $entityManager->getRepository(Experience::class);
        return $experienceRepository->findBy([], ['id' => 'DESC']);
    }

    public function getProjetType()
    {
        $entityManager = $this->registry->getManager();
        $projetTypeRepository = $entityManager->getRepository(ProjetType::class);
        return $projetTypeRepository->findAll();
    }

    public function getProjet()
    {
        $entityManager = $this->registry->getManager();
        $projetRepository = $entityManager->getRepository(Projet::class);
        return $projetRepository->createQueryBuilder('p')
            ->leftJoin('p.type', 't')
            ->addSelect('t.nom as type_nom')
            ->addSelect('p.nom')
            ->addSelect('p.imageName')
            ->addSelect('p.lien_telechargement')
            ->addSelect('p.telechargement')
            ->addSelect('p.lien_visuel')
            ->addSelect('p.visuel')
            ->getQuery()
            ->getResult();
    }

    #[Route('/', name: 'public.home')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $contact = new Contact();

        $contactForm = $this->createForm(ContactFormType::class, $contact);

        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid())
        {
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', "Votre message a bien été envoyé !");

//            $email = (new Email())
//                ->from('')
//                ->to('')
//                ->subject('Nouveau message de '.$contact->getNom())
//                ->html('<p>Bonjour, vous avez reçu un nouveau message de '.$contact->getNom().'. Pour visualiser le message, cliquez : <a href="'.$this->generateUrl('admin.competence.edit', ['id' => $contact->getId()]).'" target="_blank">ici</a></p>')
//            ;
//
//            $mailer->send($email);

            $url = $this->generateUrl('public.home').'#contact';

            return $this->redirect($url);
        }

        return $this->render('public/index.html.twig', [
            'utilisateur' => $this->utilisateurRepo(),
            'naissance' => $this->getNaissanceUser(),
            'age' => $this->getAgeUser(),
            'competenceReseau' => $this->getCompetenceReseau(),
            'competenceDeveloppement' => $this->getCompetenceDeveloppement(),
            'formation' => $this->getFormation(),
            'experience' => $this->getExperience(),
            'projets' => $this->getProjet(),
            'projet_type' => $this->getProjetType(),
            'annee' => date('Y'),
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
