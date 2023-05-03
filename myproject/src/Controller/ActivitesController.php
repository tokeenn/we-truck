<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivitesController extends AbstractController
{
    #[Route('/activites', name: 'app_activites')]
    public function index(): Response
    {
        return $this->render('activites/index.html.twig', [
            'controller_name' => 'ActivitesController',
        ]);
    }
}
