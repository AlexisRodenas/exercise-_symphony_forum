<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    #[Route("/message/{id}/edit", name: "message_edit")]
    public function editMessage(Request $request, Message $message, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $message->setSujet($message->getSujet());
            $entityManager = $doctrine->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('sujet_show', ['id' => $message->getSujet()->getId()]);
        }
        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'messageForm' => $form->createView()
        ]);
    }
    
    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route("/message/{id}/delete", name: "message_delete")]
    public function deleteMessage(Message $message = null, ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($message);
        $entityManager->flush();

        return $this->redirectToRoute('sujet_show', ['id' => $message->getSujet()->getId()]);
    }

}
