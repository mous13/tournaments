<?php

namespace Vanguard\Tournaments\Core\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Forumify\Core\Entity\User;
use Vanguard\Tournaments\Core\Repository\TournamentParticipantRepository;
use Vanguard\Tournaments\Core\Repository\TournamentRepository;

#[ORM\Entity(repositoryClass: TournamentParticipantRepository::class)]
#[ORM\Table(name: "tournaments_tournament_participants")]

class TournamentParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tournament::class, inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private Tournament $tournament;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $seed = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function setTournament(Tournament $tournament): void
    {
        $this->tournament = $tournament;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getSeed(): ?int
    {
        return $this->seed;
    }

    public function setSeed(?int $seed): void
    {
        $this->seed = $seed;
    }
}
