<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\FriendRequest;
use App\Entity\Friendship;
use App\Entity\Ban;
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
        $user = $this->getUser();

        return $this->render('hub/main.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/user/{username}", name="pub_profile")
     */
    public function publicProfile(string $username): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $this->getUser();
        $userProfile = $userRepository->findOneByUsername($username);

        if (!$userProfile) {
            throw $this->createNotFoundException('The user does not exist');
        }
    
        $friendRequestRepository = $this->getDoctrine()->getRepository(FriendRequest::class);
        $requested = $friendRequestRepository->findOneById($user->getId(),$userProfile->getId());


        $friends = false;

        if($requested !== null) {
            $requested = true;
        } else {
            $requested = false;

            $friendshipRepository = $this->getDoctrine()->getRepository(Friendship::class);

            #check friend or not
            if( $friendshipRepository->findOneById($user, $userProfile) !== null )
            {
                $friends = true;
            }
        }

        //BEGIN Check user banned or not
        $banned = false;
        $banRepository = $this->getDoctrine()->getRepository(Ban::class);
        $ban = $banRepository->findOneById($user->getId(),$userProfile->getId());

        if($ban !== null)
        {
            $banned = true;
        }
        //END check user banned

        return $this->render('pub_profile.html.twig', ['user' => $user, 'userProfile' => $userProfile, 'requested' => $requested, 'friends' => $friends, 'banned' => $banned]);
    }
}
