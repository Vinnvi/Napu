<?php

namespace App\Entity;

use App\Repository\MatchDayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchDayRepository::class)
 */
class MatchDay
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="matchDays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\OneToMany(targetEntity=Matcha::class, mappedBy="matchDay")
     */
    private $matchas;

    public function __construct()
    {
        $this->matchas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }

    /**
     * @return Collection|Matcha[]
     */
    public function getMatchas(): Collection
    {
        return $this->matchas;
    }

    public function addMatcha(Matcha $matcha): self
    {
        if (!$this->matchas->contains($matcha)) {
            $this->matchas[] = $matcha;
            $matcha->setMatchDay($this);
        }

        return $this;
    }

    public function removeMatcha(Matcha $matcha): self
    {
        if ($this->matchas->removeElement($matcha)) {
            // set the owning side to null (unless already changed)
            if ($matcha->getMatchDay() === $this) {
                $matcha->setMatchDay(null);
            }
        }

        return $this;
    }
}
