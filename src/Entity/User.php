<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=FriendRequest::class, mappedBy="fromUser")
     */
    private $fromFriendRequests;

    /**
     * @ORM\OneToMany(targetEntity=FriendRequest::class, mappedBy="ToUser")
     */
    private $toFriendRequests;

    /**
     * @ORM\OneToMany(targetEntity=Friendship::class, mappedBy="user")
     */
    private $friends;

    /**
     * @ORM\OneToMany(targetEntity=Friendship::class, mappedBy="friend")
     */
    private $friendsWithMe;

    /**
     * @ORM\OneToMany(targetEntity=Ban::class, mappedBy="authorBan")
     */
    private $bans;

    public function __construct()
    {
        $this->fromFriendRequests = new ArrayCollection();
        $this->toFriendRequests = new ArrayCollection();

        $this->friends = new ArrayCollection();
        $this->friendsWithMe = new ArrayCollection();
        
        $this->bans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|FriendRequest[]
     */
    public function getFromFriendRequests(): Collection
    {
        return $this->fromFriendRequests;
    }

    public function addFromFriendRequest(FriendRequest $fromFriendRequest): self
    {
        if (!$this->fromFriendRequests->contains($fromFriendRequest)) {
            $this->fromFriendRequests[] = $fromFriendRequest;
            $fromFriendRequest->setFromUser($this);
        }

        return $this;
    }

    public function removeFromFriendRequest(FriendRequest $fromFriendRequest): self
    {
        if ($this->fromFriendRequests->removeElement($fromFriendRequest)) {
            // set the owning side to null (unless already changed)
            if ($fromFriendRequest->getFromUser() === $this) {
                $fromFriendRequest->setFromUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FriendRequest[]
     */
    public function getToFriendRequests(): Collection
    {
        return $this->toFriendRequests;
    }

    public function addToFriendRequest(FriendRequest $toFriendRequest): self
    {
        if (!$this->toFriendRequests->contains($toFriendRequest)) {
            $this->toFriendRequests[] = $toFriendRequest;
            $toFriendRequest->setToUser($this);
        }

        return $this;
    }

    public function removeToFriendRequest(FriendRequest $toFriendRequest): self
    {
        if ($this->toFriendRequests->removeElement($toFriendRequest)) {
            // set the owning side to null (unless already changed)
            if ($toFriendRequest->getToUser() === $this) {
                $toFriendRequest->setToUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Friendship[]
     */
    public function getFriendships(): Collection
    {
        return $this->friends;
    }

    public function addFriendship(Friendship $friendship)
    {
        $this->friends->add($friendship);
        $friendship->friend->addFriendshipWithMe($friendship);
    }

    public function addFriendshipWithMe(Friendship $friendship)
    {
        $this->friendsWithMe->add($friendship);
    }

    public function addFriend(User $friend)
    {
        $fs = new Friendship();
        $fs->setUser($this);
        $fs->setFriend($friend);
        $fs->setDate(getDate());
    }

    public function removeFriendship($friendship)
    {
        if ($this->friendWithMe->removeElement($friendship)) {
            
        }
    }

    /**
     * @return Collection|Ban[]
     */
    public function getBans(): Collection
    {
        return $this->bans;
    }

    public function addBan(Ban $ban): self
    {
        if (!$this->bans->contains($ban)) {
            $this->bans[] = $ban;
            $ban->setAuthorBan($this);
        }

        return $this;
    }

    public function removeBan(Ban $ban): self
    {
        if ($this->bans->removeElement($ban)) {
            // set the owning side to null (unless already changed)
            if ($ban->getAuthorBan() === $this) {
                $ban->setAuthorBan(null);
            }
        }

        return $this;
    }
}
