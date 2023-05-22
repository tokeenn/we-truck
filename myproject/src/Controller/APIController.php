<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class APIController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function handleJson(Request $request, HttpClientInterface $httpClient): Response
{
    $apiKey = $request->headers->get('Authorization');
    var_dump($apiKey);
    die();
    if ($apiKey !== null && $this->isApiKeyValid($apiKey)) {
            $response = $httpClient->request('GET', 'https://app.kaze.so/api/some.json', [
                'headers' => [
                    'Authorization' => $apiKey,
                ],
            ]);
          
            $data = $response->toArray();

            return $this->render('api/index.html.twig', [
                'data' => $data,
            ]);
        } else {
            return new Response(null, Response::HTTP_UNAUTHORIZED);
        }
    }

    private function isApiKeyValid(string $apiKey): bool
    {
        $validApiKey = 'YiowscTx5QEXLZoDpoj2bW8VMHfWnwYq1QUQc2nhJmnbXO6c9mtjXgiPxj16pw9DZcu79ab9hYaFJYxcRerrtHJgucFpxfNrL4C0bfPtzfKPktzLyPoqOLc1GXfKJAqn';

    return ($apiKey !== null && $apiKey === $validApiKey);
    }
}
