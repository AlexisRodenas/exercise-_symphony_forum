<?php

namespace App\Controller;

use App\Entity\Sujet;
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
}
