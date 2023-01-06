<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Entity\Message;
use App\Form\MessageFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SujetController extends AbstractController
{
    #[Route('/sujets/add', name: 'app_sujet_add')]
    #[Route('/sujets/{id}/edit', name: 'app_sujet_edit')]
    public function addEditSujet(Sujet $sujet = null, Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute("app_login");
       }
        $entityManager = $doctrine->getManager();

        if(!$sujet) {
            $sujet = new Sujet();
        }

        $form = $this->createFormBuilder($sujet)
            ->add('titre', TextType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sujet->setUser($user);
            $entityManager->persist($sujet);
            $entityManager->flush();

            return $this->redirectToRoute('sujets');
        }

        return $this->render('sujet/add_edit.html.twig', [
            'sujetForm' => $form->createView()
        ]);
    }
    

    #[Route('/sujets', name: 'sujets')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Sujet::class);
        $sujets = $repository->findAll();

        return $this->render('sujet/index.html.twig', [
            'controller_name' => 'SujetController',
            'sujets' => $sujets
        ]);
    }

    #[Route('/sujets/{id}', name: 'sujet_show')]
    public function showSujetAddMessage(Request $request, Sujet $sujet, Message $message = null, ManagerRegistry $doctrine)
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute("app_login");
       }
        $message = new Message();

        // A utiliser avec un make:form (FormType)
        $form = $this->createForm(MessageFormType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = $form->getData();

            // Ajouter au message le sujet dont l'id est dans la requête
            $message->setSujet($sujet);

            $entityManager = $doctrine->getManager();
            $message->setUser($this->getUser());
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('sujet_show', ['id' => $sujet->getId()]);
        }

        return $this->render('sujet/show.html.twig', [
            'sujet' => $sujet,
            'messageForm' => $form->createView()
        ]);
    }
    #[Route('/{id}/verrouille', name: 'sujet_verrouille')]
    public function verrouilleSujet(Sujet $sujet = null, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        if (!$sujet->isverouillage()) {
            $sujet->setverouillage(true);
        } else {
            $sujet->setverouillage(false);
        }

        $entityManager->persist($sujet);
        $entityManager->flush();

        return $this->redirectToRoute('sujets');
    }


    #[Route('/{id}/delete', name: 'sujet_delete')]
    public function deleteSujet(Sujet $sujet = null, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($sujet);
        $entityManager->flush();

        return $this->redirectToRoute('sujets');
    }
}
