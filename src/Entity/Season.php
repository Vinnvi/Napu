<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="seasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $league;

    /**
     * @ORM\OneToMany(targetEntity=MatchDay::class, mappedBy="season")
     */
    private $matchDays;

    public function __construct()
    {
        $this->matchDays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|MatchDay[]
     */
    public function getMatchDays(): Collection
    {
        return $this->matchDays;
    }

    public function addMatchDay(MatchDay $matchDay): self
    {
        if (!$this->matchDays->contains($matchDay)) {
            $this->matchDays[] = $matchDay;
            $matchDay->setSeason($this);
        }

        return $this;
    }

    public function removeMatchDay(MatchDay $matchDay): self
    {
        if ($this->matchDays->removeElement($matchDay)) {
            // set the owning side to null (unless already changed)
            if ($matchDay->getSeason() === $this) {
                $matchDay->setSeason(null);
            }
        }

        return $this;
    }
}
