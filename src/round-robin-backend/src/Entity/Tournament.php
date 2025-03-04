<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Tournament
{
    public const int TEAMS_IN_TOURNAMENT_MIN = 3;
    public const int TEAMS_IN_TOURNAMENT_MAX = 12;

    public const int NAME_LENGTH_MAX = 128;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: self::NAME_LENGTH_MAX, unique: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $updatedAt = null;

    #[ORM\Column]
    private ?int $createdAt = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'tournament')]
    private Collection $games;

    /**
     * @var Collection<int, TeamScore>
     */
    #[ORM\OneToMany(targetEntity: TeamScore::class, mappedBy: 'tournament')]
    private Collection $teamScores;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->teamScores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
    public function setDateFields(): void
    {
        $this->updatedAt = time();
        $this->createdAt = time();
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
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
            $teamScore->setTournament($this);
        }

        return $this;
    }

    public function removeTeamScore(TeamScore $teamScore): static
    {
        if ($this->teamScores->removeElement($teamScore)) {
            // set the owning side to null (unless already changed)
            if ($teamScore->getTournament() === $this) {
                $teamScore->setTournament(null);
            }
        }

        return $this;
    }
}
