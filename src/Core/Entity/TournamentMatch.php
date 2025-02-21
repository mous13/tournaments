<?php

namespace Vanguard\Tournaments\Core\Entity;


use Doctrine\ORM\Mapping as ORM;
use Vanguard\Tournaments\Core\Repository\TournamentMatchRepository;

#[ORM\Entity(repositoryClass: TournamentMatchRepository::class)]
#[ORM\Table(name: "tournaments_tournament_matches")]

class TournamentMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tournament::class, inversedBy: 'matches')]
    #[ORM\JoinColumn(nullable: false)]
    private Tournament $tournament;

    #[ORM\Column(type: 'integer')]
    private int $round;

    #[ORM\ManyToOne(targetEntity: TournamentParticipant::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?TournamentParticipant $participantOne = null;

    #[ORM\ManyToOne(targetEntity: TournamentParticipant::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?TournamentParticipant $participantTwo = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $scoreOne = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $scoreTwo = null;

    #[ORM\ManyToOne(targetEntity: TournamentParticipant::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?TournamentParticipant $winner = null;

    #[ORM\Column(type: 'integer')]
    private int $position = 0;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $previousMatchOne = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $previousMatchTwo = null;

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

    public function getRound(): int
    {
        return $this->round;
    }

    public function setRound(int $round): void
    {
        $this->round = $round;
    }

    public function getParticipantOne(): ?TournamentParticipant
    {
        return $this->participantOne;
    }

    public function setParticipantOne(?TournamentParticipant $participantOne): void
    {
        $this->participantOne = $participantOne;
    }

    public function getParticipantTwo(): ?TournamentParticipant
    {
        return $this->participantTwo;
    }

    public function setParticipantTwo(?TournamentParticipant $participantTwo): void
    {
        $this->participantTwo = $participantTwo;
    }

    public function getScoreOne(): ?int
    {
        return $this->scoreOne;
    }

    public function setScoreOne(?int $scoreOne): void
    {
        $this->scoreOne = $scoreOne;
    }

    public function getScoreTwo(): ?int
    {
        return $this->scoreTwo;
    }

    public function setScoreTwo(?int $scoreTwo): void
    {
        $this->scoreTwo = $scoreTwo;
    }

    public function getWinner(): ?TournamentParticipant
    {
        return $this->winner;
    }

    public function setWinner(?TournamentParticipant $winner): void
    {
        $this->winner = $winner;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getPreviousMatchOne(): ?TournamentMatch
    {
        return $this->previousMatchOne;
    }

    public function setPreviousMatchOne(?TournamentMatch $previousMatchOne): void
    {
        $this->previousMatchOne = $previousMatchOne;
    }

    public function getPreviousMatchTwo(): ?TournamentMatch
    {
        return $this->previousMatchTwo;
    }

    public function setPreviousMatchTwo(?TournamentMatch $previousMatchTwo): void
    {
        $this->previousMatchTwo = $previousMatchTwo;
    }
}
