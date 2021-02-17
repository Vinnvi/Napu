<?php

namespace App\Entity;

use App\Repository\BetroomRulesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BetroomRulesRepository::class)
 */
class BetroomRules
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Betroom::class, inversedBy="betroomRules", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $betroom;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $rules = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBetroom(): ?Betroom
    {
        return $this->betroom;
    }

    public function setBetroom(Betroom $betroom): self
    {
        $this->betroom = $betroom;

        return $this;
    }

    public function getRules(): ?array
    {
        return $this->rules;
    }

    public function setRules(?array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }
}
