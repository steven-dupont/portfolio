<?php

namespace App\Controller\admin;

use App\Entity\Contact;
use App\Form\admin\ContactStatutFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/contact', name: 'admin.contact.')]
class ContactController extends AbstractController
{
    private $repository;

    public function __construct(ContactRepository $repository)
    {
        $this->repository = $repository;
    }

    #[Route('', name: 'show')]
    public function index(): Response
    {
        $contact = $this->repository->findBy([], ['id' => 'DESC']);

        $menu_active = 'contact';

        return $this->render('admin/contact/show.html.twig', [
            'contacts' => $contact,
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'view', methods: ['GET', 'POST'])]
    #[ParamConverter('contact', class: Contact::class, options: ['mapping' => ['id' => 'id']])]
    public function view($id, Contact $contact, EntityManagerInterface $entityManager, Request $request, ManagerRegistry $registry): Response
    {
//        $infoContact = $this->repository->find($id);

        $infoContact = $registry->getRepository(Contact::class)->find($id);

        if(!$infoContact)
        {
            $this->addFlash('danger', "La demande de contact n'existe pas !");

            return $this->redirectToRoute('admin.contact.show');
        }
        else
        {
            $contactStatutForm = $this->createForm(ContactStatutFormType::class, $contact);

            $contactStatutForm->handleRequest($request);

            if($contactStatutForm->isSubmitted() && $contactStatutForm->isValid())
            {
                $entityManager->persist($contact);
                $entityManager->flush();

                $this->addFlash('success', "Le statut a bien été modifié !");

                return $this->redirectToRoute('admin.contact.view', ['id' => $id]);
            }
        }

        $menu_active = 'contact';

        return $this->render('admin/contact/view.html.twig', [
            'infoContact' => $infoContact,
            'contactStatutForm' => $contactStatutForm->createView(),
            'menu_active' => $menu_active,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Contact $contact, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$contact->getId(), $request->get('_token')))
        {
            $entityManager->remove($contact);
            $entityManager->flush();

            $this->addFlash('success', "La demande de contact a bien été supprimé !");
        }

        return $this->redirectToRoute('admin.contact.show');
    }
}
