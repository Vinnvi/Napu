<?php

namespace App\Entity;

use App\Repository\MatchaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatchaRepository::class)
 */
class Matcha
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Sport::class)
     */
    private $sport;

    /**
     * @ORM\OneToMany(targetEntity=BetroomMatch::class, mappedBy="matcha")
     */
    private $betroomMatches;

    /**
     * @ORM\OneToMany(targetEntity=BetRules::class, mappedBy="matcha")
     */
    private $betRules;

    public function __construct()
    {
        $this->betroomMatches = new ArrayCollection();
        $this->betRules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|BetroomMatch[]
     */
    public function getBetroomMatches(): Collection
    {
        return $this->betroomMatches;
    }

    public function addBetroomMatch(BetroomMatch $betroomMatch): self
    {
        if (!$this->betroomMatches->contains($betroomMatch)) {
            $this->betroomMatches[] = $betroomMatch;
            $betroomMatch->setMatcha($this);
        }

        return $this;
    }

    public function removeBetroomMatch(BetroomMatch $betroomMatch): self
    {
        if ($this->betroomMatches->removeElement($betroomMatch)) {
            // set the owning side to null (unless already changed)
            if ($betroomMatch->getMatcha() === $this) {
                $betroomMatch->setMatcha(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BetRules[]
     */
    public function getBetRules(): Collection
    {
        return $this->betRules;
    }

    public function addBetRule(BetRules $betRule): self
    {
        if (!$this->betRules->contains($betRule)) {
            $this->betRules[] = $betRule;
            $betRule->setMatcha($this);
        }

        return $this;
    }

    public function removeBetRule(BetRules $betRule): self
    {
        if ($this->betRules->removeElement($betRule)) {
            // set the owning side to null (unless already changed)
            if ($betRule->getMatcha() === $this) {
                $betRule->setMatcha(null);
            }
        }

        return $this;
    }
}
