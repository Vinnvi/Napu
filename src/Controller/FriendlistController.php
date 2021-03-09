<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\FriendRequest;
use App\Entity\Friendship;
use App\Entity\Ban;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\User\RelationsGenerator;

class FriendlistController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    /**
     * @Route("/friendlist", name="friendlist")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        $friends = $user->getFriendships();

        $requests = $user->getToFriendRequests();

        $bans = $user->getBans();

        return $this->render('friendlist.html.twig', ['user' => $user, 'friends' => $friends, 'requests' => $requests, 'bans' => $bans]);
    }

    /**
     * @Route("/friendship/{id}", name="friendship.request.add", methods="DELETE")
     * @param RelationsGenerator $relation
     * @param User $newFriend
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function addFriendshipRequest(RelationsGenerator $relation, User $newFriend)
    {
        $user = $this->getUser();
        
        $requestCreation = $relation->addFriendRequest($user, $newFriend);
        
        if($requestCreation === true)
        {

            $this->addFlash('success', 'friendship requested with success !');

        } else 
        {

            $this->addFlash('error', 'error while creating request.');

        }


        return $this->redirectToRoute('pub_profile', ['username' => $newFriend->getUsername()]);
    }

    /**
     * @Route("/friendship/{id}", name="friendship.add", methods="CREATE")
     * @param RelationsGenerator $relation
     * @param User $newFriend
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function acceptFriendship(RelationsGenerator $relation, User $newFriend)
    {
        $user = $this->getUser();

        $relation->acceptFriendship($newFriend, $user) === true;
    

        return $this->redirectToRoute('friendlist', []);
    }

    /**
     * @Route("/friendship/reject/{id}", name="friendship.reject", methods="CREATE")
     * @param User $fromUser
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function rejectFriendship(User $fromUser)
    {
        $user = $this->getUser();

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
     * @param RelationsGenerator $relation
     * @param User $bannedUser
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function banUser(RelationsGenerator $relation, User $bannedUser)
    {
        $user = $this->getUser();

        if($relation->addBan($user, $bannedUser) === true)
        {
            $this->addFlash('success', $bannedUser->getUsername().' is now banned');
        }

        return $this->redirectToRoute('friendlist', []);
    }


    /**
     * @Route("/user/ban/{id}", name="user.unban", methods="DELETE")
     * @param RelationsGenerator $relation
     * @param User $unbannedUser
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function unbanUser(RelationsGenerator $relation, User $unbannedUser)
    {
        $user = $this->getUser();

        if($relation->removeBan($user, $unbannedUser) === true )
        {
            $this->addFlash('success', $unbannedUser->getUsername().' is now unbanned');   
        }

        return $this->redirectToRoute('pub_profile', ['username' => $unbannedUser->getUsername()]);
    }

    /**
     * @Route("/user/unrequest/{id}", name="user.unrequest", methods="DELETE")
     * @param RelationsGenerator $relation
     * @param User $requestedUser
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function unRequestUser(RelationsGenerator $relation, User $requestedUser)
    {
        $user = $this->getUser();

        if($relation->removeFriendRequest($user, $requestedUser) === true)
        {
            $this->addFlash('success', 'friendRequest has been removed');
        }

        return $this->redirectToRoute('pub_profile', ['username' => $requestedUser->getUsername()]);

    }

    /**
     * @Route("/friendship/remove/{id}", name="friendship.remove", methods="DELETE")
     * @param RelationsGenerator $relation
     * @param User $removedUser
     * @return Symfony\Component\HttpFoundation\Response;
     */
    public function removeFriendship(RelationsGenerator $relation, User $removedUser)
    {
        $user = $this->getUser();

        if($relation->removeFriendship($user, $removedUser) === true)
        {
            $this->addFlash('success', 'friendship has been removed');
        }

        return $this->redirectToRoute('pub_profile', ['username' => $removedUser->getUsername()]);

    }
}
