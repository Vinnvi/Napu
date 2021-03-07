<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\FriendRequest;
use App\Entity\Friendship;
use App\Entity\Ban;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\User\RelationsGenerator;

class HubController extends AbstractController
{

    /**
     * @Route("/", name="app_hub")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('hub/main.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/user/{username}", name="pub_profile")
     * @param RelationGenerator $relation
     * @param String $username
     */
    public function publicProfile(RelationsGenerator $relation, string $username): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $this->getUser();
        $userProfile = $userRepository->findOneByUsername($username);

        if (!$userProfile) {
            throw $this->createNotFoundException('The user does not exist');
        }
    

        $friends = false;
        $requested = false;

        $banned = $relation->hasBanned($user, $userProfile);

        if($requested === false) 
        {
            $friends = $relation->areFriends($user, $userProfile);
            $requested = $relation->hasRequested($user, $userProfile);
        }

        return $this->render('pub_profile.html.twig', ['user' => $user, 'userProfile' => $userProfile, 'requested' => $requested, 'friends' => $friends, 'banned' => $banned]);
    }
}
