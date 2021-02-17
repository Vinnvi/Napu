<?php

namespace App\Entity;

use App\Repository\BetroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BetroomRepository::class)
 */
class Betroom
{

    const STATUS = [
        0 => 'enabled',
        1 => 'disabled'
    ];


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=BetroomMatch::class, mappedBy="betroom")
     */
    private $betroomMatches;

    /**
     * @ORM\OneToMany(targetEntity=BetRules::class, mappedBy="betroom")
     */
    private $betRules;

    /**
     * @ORM\OneToOne(targetEntity=BetroomRules::class, mappedBy="betroom", cascade={"persist", "remove"})
     */
    private $betroomRules;

    public function __construct()
    {
        $this->public = false;
        $this->status = 0;
        $this->date = new \Datetime();
        $this->betroomMatches = new ArrayCollection();
        $this->betRules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
            $betroomMatch->setBetroom($this);
        }

        return $this;
    }

    public function removeBetroomMatch(BetroomMatch $betroomMatch): self
    {
        if ($this->betroomMatches->removeElement($betroomMatch)) {
            // set the owning side to null (unless already changed)
            if ($betroomMatch->getBetroom() === $this) {
                $betroomMatch->setBetroom(null);
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
            $betRule->setBetroom($this);
        }

        return $this;
    }

    public function removeBetRule(BetRules $betRule): self
    {
        if ($this->betRules->removeElement($betRule)) {
            // set the owning side to null (unless already changed)
            if ($betRule->getBetroom() === $this) {
                $betRule->setBetroom(null);
            }
        }

        return $this;
    }

    public function getBetroomRules(): ?BetroomRules
    {
        return $this->betroomRules;
    }

    public function setBetroomRules(BetroomRules $betroomRules): self
    {
        // set the owning side of the relation if necessary
        if ($betroomRules->getBetroom() !== $this) {
            $betroomRules->setBetroom($this);
        }

        $this->betroomRules = $betroomRules;

        return $this;
    }
}
