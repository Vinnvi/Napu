<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @Route("/betroom/test", name="betroom.test")
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function viewTest()
    {
        $user = $this->getUser();

        $this->fill_teams();
        
        return $this->render('test.html.twig', ['user' => $user]);
    }

    /**
     * fill database with teams 
     */
    public function fill_teams()
    {
        $response = $this->client->request('GET', 'https://api.football-data.org/v2/competitions/FL1/matches?matchday=11', [
            'headers' => [
                'Accept' => 'application/json',
                'X-Auth-Token' => '145e132ff7154506ac62251d4b9c16bf'
            ],
        ]);

        dump($response->getStatusCode());
        dump($response->toArray());

        return;
    }
}