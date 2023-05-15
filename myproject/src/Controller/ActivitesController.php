<?php

namespace App\Controller;

use DOMDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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