<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\Friendship;
use App\Entity\FriendRequest;
use App\Entity\Ban;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RelationsGenerator
{

    private $em;

    private $session;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
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

        //cant request if one of them has banned the other
        if($this->banExists($user1, $user2) === true)
        {
            return false;
        }
        
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
     * check if user1 has banned user2 AND vice versa
     */
    public function areBanned($user1, $user2): bool
    {
        if($this->hasBanned($user1, $user2) === true && $this->hasBanned($user2, $user1) === true)
        {
            return true;
        }

        return false;
    }

    /**
     * @param User $user1
     * @param User $user2
     * check if at least one of the user has banned the other
     */
    public function banExists($user1, $user2): bool
    {
        if($this->hasBanned($user1, $user2) === true || $this->hasBanned($user2, $user1) === true)
        {
            return true;
        }

        return false;
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

        $this->session->getFlashBag()->add('success', 'Success! '.$user2->getUsername().' is now banned');

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

        $this->session->getFlashBag()->add('success', 'Success! '.$user2->getUsername().' is not your friend anymore');

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

        $this->session->getFlashBag()->add('success', 'Success! Friend request has been removed');

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

        $this->session->getFlashBag()->add('success', 'Success! '.$user2->getUsername().' is now unbanned');

        return true;
    }

    /**
     * @param User $initiator
     * @param User $acceptor
     * 
     * user acceptor accepts a friendrequest from user initiator
     */
    public function acceptFriendship(User $initiator, User $acceptor): bool
    {
        //check if friendRequest really exists
        if($this->hasRequested($initiator, $acceptor) === false)
        {
            return false;
        }
        
        $this->createFriendship($initiator, $acceptor);

        $this->removeFriendRequest($initiator, $acceptor);

        $this->session->getFlashBag()->add('success', 'Success! '.$initiator->getUsername().' is now yout friend');

        return true;
    }

    /**
     * @param User $user1
     * @param User $user2
     * create a friendship between two users
     */
    public function createFriendship(User $user1, User $user2):bool
    {
        $friendship1 = new Friendship();
        $friendship1->setUser($user1);
        $friendship1->setFriend($user2);

        $friendship2 = new Friendship();
        $friendship2->setUser($user2);
        $friendship2->setFriend($user1);

        $this->em->persist($friendship1);
        $this->em->persist($friendship2);
        $this->em->flush();

        return true;
    }


        /**
     * @param User $initiator
     * @param User $refuser
     * 
     * user refuser refuses a friendrequest from user initiator
     */
    public function rejectFriendship(User $initiator, User $refuser): bool
    {
        //check if friendRequest really exists
        if($this->hasRequested($initiator, $refuser) === false)
        {
            return false;
        }

        $this->removeFriendRequest($initiator, $refuser);

        $this->session->getFlashBag()->add('success', 'Success! Friend request from '.$initiator->getUsername().' has been refused');

        return true;
    }
}