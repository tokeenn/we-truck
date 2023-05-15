<?php

namespace App\Controller;

use DOMDocument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActualitesController extends AbstractController
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
                'categ' => $ligne->getElementsByTagName("category")[0]->nodeValue,
                'title' => $ligne->getElementsByTagName("title")[0]->nodeValue,
                'date' => $ligne->getElementsByTagName("pubDate")[0]->nodeValue,
                'desc' => $ligne->getElementsByTagName("description")[0]->nodeValue,
                'link' => $ligne->getElementsByTagName("link")[0]->nodeValue,
            ];
            // Ajouter l'élément au tableau associatif de la catégorie correspondante
            $category = $item['categ'];
            if (!isset($feed[$category])) {
                $feed[$category] = array();
            }
            $feed[$category][] = $item;
        }
        return $feed;
    }

    #[Route('/actualites', name: 'app_actualites')]
    public function index(Request $request ): Response
    {
        $category = $request->query->get('category');

        return $this->render('actualites/index.html.twig', [
            'controller_name' => 'ActualitesController',
            'loadFeed' => $this->loadFeed(),
            'category' => $category,
        ]);
    }
}