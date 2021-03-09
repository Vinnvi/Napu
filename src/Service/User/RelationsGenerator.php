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
    public function addFriendRequest(User $user1,User $user2)
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
    public function createFriendRequest(User $user1,User $user2): bool
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
     * check if user1 has banned user2
     */
    public function hasBanned(User $user1,User $user2): bool
    {
        $ban = $this->em->getRepository(Ban::class)->findOneById($user1->getId(), $user2->getId());

        if($ban === NULL)
        {
            return false;
        }

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * add a ban from user1 to user 2
     */
    public function addBan(User $user1,User $user2): bool
    {
        //check if user2 is not already banned from user1
        if($this->hasBanned($user1, $user2) === true)
        {
            return false;
        }

        //if exists, remvove friendship between both users
        $this->removeFriendship($user1, $user2);

        //if exists, remove friendrequest from user1 to user2
        $this->removeFriendRequest($user1, $user2);

        $this->createBan($user1, $user2);

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * create ban object and save it in DB
     */
    public function createBan(User $user1, User $user2): bool
    {
        //create object
        $ban = new Ban();
        $ban->setAuthorBan($user1);
        $ban->setBannedUser($user2);

        //save it in DB
        $this->em->persist($ban);
        $this->em->flush();

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * remove a friendship between two users
     */
    public function removeFriendship(User $user1, User $user2): bool
    {
        if($this->areFriends($user1, $user2) === false)
        {
            return false;
        }

        //recover both friendship objects (user1 to user2 and user2 to user1)
        $friendship1 = $this->em->getRepository(Friendship::class)->findOneById($user1->getId(), $user2->getId());
        $friendship2 = $this->em->getRepository(Friendship::class)->findOneById($user2->getId(), $user1->getId());

        if($friendship1 === null || $friendship2 === null)
        {
            return false;
        }

        //delete them from DB
        $this->em->remove($friendship1);
        $this->em->remove($friendship2);

        $this->em->flush();

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * 
     * remove a friendRequest from user1 to user 2 
     */
    public function removeFriendRequest($user1, $user2): bool
    {
        if($this->isFriendRequest($user1, $user2) === false)
        {
            return false;
        }

        //get friendrequest object
        $friendRequest = $this->em->getRepository(FriendRequest::class)->findOneById($user1->getId(), $user2->getId());

        if($friendRequest === null)
        {
            return false;
        }

        //remove it
        $this->em->remove($friendRequest);
        $this->em->flush();

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * 
     * remove a ban from user1 to user 2 
     */
    public function removeBan($user1, $user2): bool
    {
        if($this->hasBanned($user1, $user2) === false)
        {
            return false;
        }

        //get ban object
        $ban = $this->em->getRepository(Ban::class)->findOneById($user1->getId(), $user2->getId());

        if($ban === null)
        {
            return false;
        }

        //remove it
        $this->em->remove($ban);
        $this->em->flush();

        return true;
    }
}