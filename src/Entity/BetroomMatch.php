<?php

namespace App\Entity;

use App\Repository\BetroomMatchRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BetroomMatchRepository::class)
 */
class BetroomMatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Betroom::class, inversedBy="betroomMatches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $betroom;

    /**
     * @ORM\ManyToOne(targetEntity=Matcha::class, inversedBy="betroomMatches")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matcha;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBetroom(): ?Betroom
    {
        return $this->betroom;
    }

    public function setBetroom(?Betroom $betroom): self
    {
        $this->betroom = $betroom;

        return $this;
    }

    public function getMatcha(): ?Matcha
    {
        return $this->matcha;
    }

    public function setMatcha(?Matcha $matcha): self
    {
        $this->matcha = $matcha;

        return $this;
    }
}
