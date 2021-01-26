<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HubController extends AbstractController
{
    /**
     * @Route("/", name="app_hub")
     */
    public function index(): Response
    {
        return $this->render('hub/main.html.twig', []);
    }
}
