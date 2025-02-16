<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Front\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TournamentsCenterController extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Route('/center', name: 'center')]
    public function __invoke(): Response
    {
        return $this->render('@VanguardTournaments/front/tournaments_center.html.twig', [
        ]);
    }
}