<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\FriendRequest;
use App\Entity\Friendship;
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

        $friends = $user->getFriendships();

        $requests = $user->getToFriendRequests();

        return $this->render('friendlist.html.twig', ['user' => $user, 'friends' => $friends, 'requests' => $requests]);
    }

    /**
     * @Route("/friendship/{id}", name="friendship.request.add", methods="DELETE")
     * @param User $newFriend
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function addFriendshipRequest(User $newFriend)
    {
        $user = $this->security->getUser();
        $friendRequest = new FriendRequest();
        $friendRequest->setFromUser($user);
        $friendRequest->setToUser($newFriend);

        $this->em->persist($friendRequest);
        $this->em->flush();

        $this->addFlash('success', 'friendship requested with success !');

        return $this->redirectToRoute('pub_profile', ['username' => $newFriend->getUsername()]);
    }

    /**
     * @Route("/friendship/{id}", name="friendship.add", methods="CREATE")
     * @param User $newFriend
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function acceptFriendship(User $newFriend)
    {
        $user = $this->security->getUser();

        // create both friendship
        $friendship1 = new Friendship();
        $friendship2 = new Friendship();

        $friendship1->setUser($user);
        $friendship1->setFriend($newFriend);

        $friendship2->setUser($newFriend);
        $friendship2->setFriend($user);

        //delete friendRequest
        // get friendRequest
        $friendRequestRepository = $this->getDoctrine()->getRepository(FriendRequest::class);
        $friendRequest = $friendRequestRepository->findOneById($newFriend->getId(), $user->getId());

        //delete it if friendship request found
        if($friendRequest !== null)
        {
            $this->em->remove($friendRequest);

            //save them
            $this->em->persist($friendship1);
            $this->em->persist($friendship2);

            $this->em->flush();
        } else {
            $this->addFlash('success', 'Error : friend request from this user not found');
        }



        //end save

        $this->addFlash('success', 'Success! Now '.$newFriend->getUsername().' and you are friends !');

        return $this->redirectToRoute('friendlist', []);
    }

    /**
     * @Route("/friendship/reject/{id}", name="friendship.reject", methods="CREATE")
     * @param User $fromUser
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function rejectFriendship(User $fromUser)
    {
        $user = $this->security->getUser();

        //delete request from this user

        // get friendRequest
        $friendRequestRepository = $this->getDoctrine()->getRepository(FriendRequest::class);
        $friendRequest = $friendRequestRepository->findOneById($fromUser->getId(), $user->getId());

        //delete it if friendship request found
        if($friendRequest !== null)
        {
            $this->em->remove($friendRequest);
            $this->em->flush();
            $this->addFlash('success', 'friendship request rejected with success');
        } else {
            $this->addFlash('error', 'friendship request not found');
        }
        
        //end delete

        return $this->redirectToRoute('friendlist', []);
    }
}
