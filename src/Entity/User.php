<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var Collection<int, UserTeamHistory>
     */
    #[ORM\OneToMany(targetEntity: UserTeamHistory::class, mappedBy: 'user')]
    #[ORM\OrderBy(['fromDate' => 'ASC'])]
    private Collection $teamHistories;

    public function __construct()
    {
        $this->teamHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, UserTeamHistory>
     */
    public function getTeamHistories(): Collection
    {
        return $this->teamHistories;
    }

    public function addTeamHistory(UserTeamHistory $teamHistory): static
    {
        if (!$this->teamHistories->contains($teamHistory)) {
            $this->teamHistories->add($teamHistory);
            $teamHistory->setUser($this);
        }

        return $this;
    }

    public function removeTeamHistory(UserTeamHistory $teamHistory): static
    {
        if ($this->teamHistories->removeElement($teamHistory)) {
            // set the owning side to null (unless already changed)
            if ($teamHistory->getUser() === $this) {
                $teamHistory->setUser(null);
            }
        }

        return $this;
    }

    public function getTeam(\DateTimeInterface $date): ?Team
    {
        return $this->teamHistories->filter(
            function (UserTeamHistory $history) use ($date) {
                return ($history->getFromDate() === null || $history->getFromDate() <= $date)
                    && ($history->getToDate() === null || $history->getToDate() >= $date);
            }
        )->findFirst(fn () => true)?->getTeam();
    }

    public function __toString(): string
    {
        return strval($this->username);
    }
}
