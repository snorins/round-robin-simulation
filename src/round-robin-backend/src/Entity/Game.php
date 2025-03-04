<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournament $tournament = null;

    #[ORM\Column]
    private ?int $round = null;

    #[ORM\Column]
    private ?int $updatedAt = null;

    #[ORM\Column]
    private ?int $createdAt = null;

    /**
     * @var Collection<int, TeamScore>
     */
    #[ORM\OneToMany(targetEntity: TeamScore::class, mappedBy: 'game')]
    private Collection $teamScores;

    public function __construct()
    {
        $this->teamScores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(int $round): static
    {
        $this->round = $round;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = time();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->updatedAt = time();
        $this->createdAt = time();
    }

    /**
     * @return Collection<int, TeamScore>
     */
    public function getTeamScores(): Collection
    {
        return $this->teamScores;
    }

    public function addTeamScore(TeamScore $teamScore): static
    {
        if (!$this->teamScores->contains($teamScore)) {
            $this->teamScores->add($teamScore);
            $teamScore->setGame($this);
        }

        return $this;
    }

    public function removeTeamScore(TeamScore $teamScore): static
    {
        if ($this->teamScores->removeElement($teamScore)) {
            // set the owning side to null (unless already changed)
            if ($teamScore->getGame() === $this) {
                $teamScore->setGame(null);
            }
        }

        return $this;
    }
}
