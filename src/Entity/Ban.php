<?php

namespace App\Entity;

use App\Repository\BanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=BanRepository::class)
 */
class Ban
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $authorBan;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $bannedUser;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorBan(): ?User
    {
        return $this->authorBan;
    }

    public function setAuthorBan(?User $authorBan): self
    {
        $this->authorBan = $authorBan;

        return $this;
    }

    public function getBannedUser(): ?User
    {
        return $this->bannedUser;
    }

    public function setBannedUser(?User $bannedUser): self
    {
        $this->bannedUser = $bannedUser;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
