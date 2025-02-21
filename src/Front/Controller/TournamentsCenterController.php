<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Front\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vanguard\Tournaments\Core\Repository\TournamentRepository;

class TournamentsCenterController extends AbstractController
{

    public function __construct(
        private readonly TournamentRepository $tournamentRepository,
    ) {
    }

    #[Route('/center', name: 'center')]
    public function __invoke(): Response
    {
        $tournaments = $this->tournamentRepository->findAll();
        return $this->render('@VanguardTournaments/front/tournaments_center.html.twig', [
            'tournaments' => $tournaments,
        ]);
    }
}