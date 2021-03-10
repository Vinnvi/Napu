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
        
        return $this->render('test.html.twig', ['user' => $user]);
    }
}