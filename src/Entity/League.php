<?php

namespace App\Entity;

use App\Repository\LeagueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeagueRepository::class)
 */
class League
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class, inversedBy="leagues")
     */
    private $sport;

    /**
     * @ORM\OneToMany(targetEntity=MatchDay::class, mappedBy="league")
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

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
            $matchDay->setLeague($this);
        }

        return $this;
    }

    public function removeMatchDay(MatchDay $matchDay): self
    {
        if ($this->matchDays->removeElement($matchDay)) {
            // set the owning side to null (unless already changed)
            if ($matchDay->getLeague() === $this) {
                $matchDay->setLeague(null);
            }
        }

        return $this;
    }
}
