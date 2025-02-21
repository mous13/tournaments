<?php

namespace Vanguard\Tournaments\Core\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vanguard\Tournaments\Core\Repository\TournamentRepository;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
#[ORM\Table(name: "tournaments_tournament")]

class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\Column(length: 50)]
    private string $participantType;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $endAt = null;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentParticipant::class, cascade: ['persist', 'remove'])]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentMatch::class, cascade: ['persist', 'remove'])]
    private Collection $matches;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255,  nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255,  nullable: true)]
    private ?string $visibility = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $participantLimit = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->participants = new ArrayCollection();
        $this->matches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getParticipantType(): string
    {
        return $this->participantType;
    }

    public function setParticipantType(string $participantType): void
    {
        $this->participantType = $participantType;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getEndAt(): ?DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTime $endAt): void
    {
        $this->endAt = $endAt;
    }

    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function setParticipants(Collection $participants): void
    {
        $this->participants = $participants;
    }

    public function getMatches(): Collection
    {
        return $this->matches;
    }

    public function setMatches(Collection $matches): void
    {
        $this->matches = $matches;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(?string $visibility): void
    {
        $this->visibility = $visibility;
    }

    public function getParticipantLimit(): ?int
    {
        return $this->participantLimit;
    }

    public function setParticipantLimit(?int $participantLimit): void
    {
        $this->participantLimit = $participantLimit;
    }
}
