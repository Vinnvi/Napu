<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HubController extends AbstractController
{


    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }


    /**
     * @Route("/", name="app_hub")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();

        return $this->render('hub/main.html.twig', ['user' => $user]);
    }
}
