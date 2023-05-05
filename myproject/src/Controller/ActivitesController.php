<?php

namespace App\Controller;

use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivitesController extends AbstractController
{

    private $listeNews;

    public function __construct()
    {
        $this->listeNews = $this->loadFeed();
    }

    public function getListeNews()
    {
        return $this->listeNews;
    }

    private function loadFeed()
    {
        $rss = new DOMDocument();
        $rss->load("https://www.lepilote.com/fr/piloterss/Disruptions");


        $feed = array();
        foreach ($rss->getElementsByTagName("item") as $key => $ligne) {
            $item = [
                'id' => $key,
                'category' => $ligne->getElementsByTagName("category")[0]->nodeValue,
                'title' => $ligne->getElementsByTagName("title")[0]->nodeValue,
                'date' => $ligne->getElementsByTagName("pubDate")[0]->nodeValue,
                'desc' => $ligne->getElementsByTagName("description")[0]->nodeValue,
                'link' => $ligne->getElementsByTagName("link")[0]->nodeValue,
            ];
            // Ajouter l'élément au tableau associatif de la catégorie correspondante
            $category = $item['category'];
            if (!isset($feed[$category])) {
                $feed[$category] = array();
            }
            $feed[$category][] = $item;
        }
        return $feed;
    }

    #[Route('/activites', name: 'app_activites')]
    public function index(): Response
    {
        return $this->render('activites/index.html.twig', [
            'controller_name' => 'ActivitesController',
            'loadFeed' => $this->loadFeed(),
        ]);
    }
}
