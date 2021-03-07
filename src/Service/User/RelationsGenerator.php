<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\Friendship;
use App\Entity\FriendRequest;
use App\Entity\Ban;
use Doctrine\ORM\EntityManagerInterface;

class RelationsGenerator
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user1
     * @param User $user2
     * 
     * check if 2 users are friends or not
     */
    public function areFriends(User $user1, User $user2): bool
    {
        $friendship = $this->em->getRepository(Friendship::class)->findOneById($user1, $user2);
        
        if($friendship === NULL)
        {
            return false;
        }

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * 
     * check if user1 has sent a friendrequest to user2
     */
    public function hasRequested(User $user1, User $user2): bool
    {
        $friendRequest = $this->em->getRepository(FriendRequest::class)->findOneById($user1, $user2);

        if($friendRequest === NULL)
        {
            return false;
        }

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * check is there is a friend request between both users
     */
    public function isFriendRequest(User $user1, User $user2): bool
    {
        if($this->hasRequested($user1, $user2) === TRUE || $this->hasRequested($user2, $user1) === TRUE)
        {
            return true;
        }

        return false;
    }

    /**
     * @param User $user1
     * @param User $user2
     * 
     * add a friendRequest from user1 to user2
     */
    public function addFriendRequest($user1, $user2)
    {

        if($this->areFriends($user1, $user2) === true)
        {
            return false;
        }

        if($this->hasRequested($user1, $user2) === true)
        {
            return false;
        }

        //user can't add himself as friend
        if($user1->getId() === $user2->getId())
        {
            return false;
        }

        //TODO : check banned user

        
        $this->createFriendRequest($user1, $user2);

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * 
     * create friendRequest object 
     */
    public function createFriendRequest($user1, $user2): bool
    {
        $request = new FriendRequest();
        $request->setFromUser($user1);
        $request->setToUser($user2);

        $this->em->persist($request);
        $this->em->flush();

        return true;
    }


    /**
     * @param User $user1
     * @param User $user2
     */
    public function hasBanned($user1, $user2): bool
    {
        $ban = $this->em->getRepository(Ban::class)->findOneById($user1->getId(), $user2->getId());

        if($ban === NULL)
        {
            return false;
        }

        return true;
    }
}