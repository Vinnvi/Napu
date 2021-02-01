<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\FriendRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class FriendlistController extends AbstractController
{


    /**
     * @var Security
     */
    private $security;


    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
       $this->security = $security;
       $this->em = $entityManager;
    }


    /**
     * @Route("/friendlist", name="friendlist")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();

        $friends = $user->getFriendship();

        $requests = $user->getFromFriendRequests();

        dump($requests);

        return $this->render('friendlist.html.twig', ['user' => $user, 'friends' => $friends, 'requests' => $requests]);
    }

    /**
     * @Route("/friendship/{id}", name="friendship.request.add", methods="DELETE")
     * @param User $newFriend
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function addFriendship(User $newFriend)
    {
        $user = $this->security->getUser();
        $friendRequest = new FriendRequest();
        $friendRequest->setFromUser($user);
        $friendRequest->setToUser($newFriend);

        $this->em->persist($friendRequest);
        $this->em->flush();

        return $this->redirectToRoute('pub_profile', ['username' => $newFriend->getUsername()]);
    }
}
