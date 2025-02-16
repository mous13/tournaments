<?php

namespace Vanguard\Tournaments\Core\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Forumify\Core\Repository\AbstractRepository;
use Vanguard\Tournaments\Core\Entity\Tournament;

class TournamentRepository extends AbstractRepository
{
    public static function getEntityClass(): string
    {
        return Tournament::class;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }
}
