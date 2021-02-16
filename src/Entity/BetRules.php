<?php

namespace App\Entity;

use App\Repository\BetRulesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BetRulesRepository::class)
 */
class BetRules
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Betroom::class, inversedBy="betRules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $betroom;

    /**
     * @ORM\ManyToOne(targetEntity=Matcha::class, inversedBy="betRules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matcha;

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
