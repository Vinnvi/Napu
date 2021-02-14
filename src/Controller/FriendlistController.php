<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\FriendRequest;
use App\Entity\Friendship;
use App\Entity\Ban;
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

        $bans = $user->getBans();

        return $this->render('friendlist.html.twig', ['user' => $user, 'friends' => $friends, 'requests' => $requests, 'bans' => $bans]);
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

    /**
     * @Route("/user/ban/{id}", name="user.ban", methods="CREATE")
     * @param User $bannedUser
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function banUser(User $bannedUser)
    {
        $user = $this->security->getUser();

        //BEGIN set ban
        $ban = new Ban();
        $ban->setAuthorBan($user);
        $ban->setBannedUser($bannedUser);
        $this->em->persist($ban);
        //END set ban

        //BEGIN remove friendship(if exists)
        $friendshipRepository = $this->getDoctrine()->getRepository(Friendship::class);
        $friendship1 = $friendshipRepository->findOneById($user->getId(), $bannedUser->getId());
        $friendship2 = $friendshipRepository->findOneById($bannedUser->getId(), $user->getId());

        if($friendship1 !== null && $friendship2 !== null)
        {
            $this->em->remove($friendship1);
            $this->em->remove($friendship2);
        }
        //END remove friendship

        //BEGIN remove friend requests (if exists)
        $friendRequestRepository = $this->getDoctrine()->getRepository(FriendRequest::class);
        $friendRequest = $friendRequestRepository->findOneById($user->getId(), $bannedUser->getId());
        if($friendRequest !== null)
        {
            $this->em->remove($friendRequest);
        }

        $friendRequest = $friendRequestRepository->findOneById($bannedUser->getId(), $user->getId());
        if($friendRequest !== null)
        {
            $this->em->remove($friendRequest);
        }
        //END remove friend requests

        //Persist changes
        $this->em->flush();

        $this->addFlash('success', $bannedUser->getUsername().' is now banned');


        return $this->redirectToRoute('friendlist', []);
    }


    /**
     * @Route("/user/ban/{id}", name="user.unban", methods="DELETE")
     * @param User $unbannedUser
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function unbanUser(User $unbannedUser)
    {
        $user = $this->security->getUser();

        //BEGIN remove ban(if exists)
        $banRepository = $this->getDoctrine()->getRepository(Ban::class);
        $ban = $banRepository->findOneById($user->getId(), $unbannedUser->getId());

        if($ban !== null)
        {
            $this->em->remove($ban);

        } else {
            
        }
        //END remove ban


        //Persist changes
        $this->em->flush();

        $this->addFlash('success', $unbannedUser->getUsername().' is now unbanned');


        return $this->redirectToRoute('pub_profile', ['username' => $unbannedUser->getUsername()]);
    }

    /**
     * @Route("/user/unrequest/{id}", name="user.unrequest", methods="DELETE")
     * @param User $requestedUser
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function unRequestUser(User $requestedUser)
    {
        $user = $this->security->getUser();

        //BEGIN get friendRequest
        $friendRequestRepository = $this->getDoctrine()->getRepository(FriendRequest::class);
        $friendRequest = $friendRequestRepository->findOneById($user, $requestedUser);

        //END get friendRequest

        //check if we have friendrequest
        if($friendRequest === null)
        {
            $this->addFlash('error', 'You have no friendRequest with '.$requestedUser->getUsername());
            return $this->redirectToRoute('pub_profile', ['username' => $requestedUser->getUsername()]);
        }

        //if yes, remove it
        $this->em->remove($friendRequest);

        //Persist changes
        $this->em->flush();

        $this->addFlash('success', 'friendRequest has been removed');


        return $this->redirectToRoute('pub_profile', ['username' => $requestedUser->getUsername()]);

    }

    /**
     * @Route("/friendship/remove/{id}", name="friendship.remove", methods="DELETE")
     * @param User $removedUser
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function removeFriendship(User $removedUser)
    {
        $user = $this->security->getUser();

        //BEGIN get friendships
        $friendshipRepository= $this->getDoctrine()->getRepository(Friendship::class);
        $friendship1 = $friendshipRepository->findOneById($user, $removedUser);

        if($friendship1 === null)
        {
            $this->addFlash('error', 'You are not friends');
            return $this->redirectToRoute('pub_profile', ['username' => $requestedUser->getUsername()]);
        }

        $friendship2 = $friendshipRepository->findOneById($removedUser, $user);

        if($friendship2 === null)
        {
            $this->addFlash('error', 'You are not friends');
            return $this->redirectToRoute('pub_profile', ['username' => $requestedUser->getUsername()]);
        }

        //END get friendships

        //remove them
        $this->em->remove($friendship1);
        $this->em->remove($friendship2);

        //Persist changes
        $this->em->flush();

        $this->addFlash('success', 'friendship has been removed');


        return $this->redirectToRoute('pub_profile', ['username' => $requestedUser->getUsername()]);

    }
}
