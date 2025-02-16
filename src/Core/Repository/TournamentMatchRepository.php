<?php

namespace Vanguard\Tournaments\Core\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Forumify\Core\Repository\AbstractRepository;
use Vanguard\Tournaments\Core\Entity\TournamentMatch;

class TournamentMatchRepository extends AbstractRepository
{
    public static function getEntityClass(): string
    {
        return TournamentMatch::class;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentMatch::class);
    }
}
