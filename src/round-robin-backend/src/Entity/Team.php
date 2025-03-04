<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Team
{
    /**
     * Used as a team that represents "no opponent" in rounds
     * for tournaments in which there are odd number of teams.
     *
     * If any team matches this team — game is skipped.
     */
    public const string NAME_NO_OPPONENT = 'Bye';

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
     * @var Collection<int, TeamScore>
     */
    #[ORM\OneToMany(targetEntity: TeamScore::class, mappedBy: 'team')]
    private Collection $teamScores;

    public function __construct()
    {
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
            $teamScore->setTeam($this);
        }

        return $this;
    }

    public function removeTeamScore(TeamScore $teamScore): static
    {
        if ($this->teamScores->removeElement($teamScore)) {
            // set the owning side to null (unless already changed)
            if ($teamScore->getTeam() === $this) {
                $teamScore->setTeam(null);
            }
        }

        return $this;
    }
}
