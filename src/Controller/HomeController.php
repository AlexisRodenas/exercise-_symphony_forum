<?php

namespace App\Controller;

use App\Entity\Sujet;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_root')]
    #[Route('/home', name: 'app_home')]

    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Sujet::class);
        $sujets = $repository->findAll();
        
        return $this->render('home/index.html.twig');   
    }
}
