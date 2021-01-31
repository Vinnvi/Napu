<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FriendlistController extends AbstractController
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
     * @Route("/friendlist", name="friendlist")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();

        $friends = $user->getFriendship();

        return $this->render('friendlist.html.twig', ['user' => $user, 'friends' => $friends]);
    }
}
