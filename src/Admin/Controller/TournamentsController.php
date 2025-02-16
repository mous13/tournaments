<?php

namespace Vanguard\Tournaments\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tournaments', name: 'tournaments')]
#[IsGranted('tournaments.admin.manage')]
class TournamentsController extends AbstractController
{
    public function __construct(
    ){}

    #[Route('/list', name: '_list')]
    public function list(Request $request): Response
    {
        return $this->render('@VanguardTournaments/admin/tournaments.html.twig',
            []);
    }
}