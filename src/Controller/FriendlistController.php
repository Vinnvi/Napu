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
        
        $relation->addFriendRequest($user, $newFriend);

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
     * @param RelationsGenerator $relation
     * @param User $fromUser
     * @return Symfony\Component\HttpFoundation\Response;
    */
    public function rejectFriendship(RelationsGenerator $relation, User $fromUser)
    {
        $user = $this->getUser();

        $relation->rejectFriendship($fromUser, $user);

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

        $relation->addBan($user, $bannedUser);

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

        $relation->removeBan($user, $unbannedUser);

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

        $relation->removeFriendRequest($user, $requestedUser);
        
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

        $relation->removeFriendship($user, $removedUser);

        return $this->redirectToRoute('pub_profile', ['username' => $removedUser->getUsername()]);
    }
}
