<?php

namespace Vanguard\Tournaments\Core\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Forumify\Core\Repository\AbstractRepository;
use Vanguard\Tournaments\Core\Entity\TournamentParticipant;

class TournamentParticipantRepository extends AbstractRepository
{
    public static function getEntityClass(): string
    {
        return TournamentParticipant::class;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentParticipant::class);
    }
}
