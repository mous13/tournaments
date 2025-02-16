<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Front\Controller;

use Forumify\Core\Service\MediaService;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vanguard\Tournaments\Core\Entity\Tournament;
use Vanguard\Tournaments\Core\Repository\TournamentRepository;
use Vanguard\Tournaments\Front\Form\TournamentType;

#[Route('/tournaments', name: 'tournaments')]
class TournamentController extends AbstractController
{
    public function __construct(
        private readonly MediaService $mediaService,
        private readonly FilesystemOperator $logoStorage,
        private readonly TournamentRepository $tournamentRepository,
    ) {}

    #[Route('/new', name: '_new')]
    public function newTournament(Request $request, Tournament $tournament): Response
    {
        $form = $this->createForm(TournamentType::class, $tournament);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tournament = $form->getData();

            $tournamentImage = $form->get('image')->getData();

            $tournament->setCreatedAt(new \DateTime());

            if ($tournamentImage) {
                $tournament->setImage(
                    $this->getParameter('tournaments.logo.storage.path') . '/' .
                    $this->mediaService->saveToFilesystem($this->logoStorage, $tournamentImage)
                );
            }
            $this->tournamentRepository->save($tournament);

            $this->addFlash('success', 'Created Tournament');
            return $this->redirectToRoute('tournaments_center');
        }
        return $this->render('@VanguardTournaments/front/tournaments/new.html.twig', [
            'form' => $form->createView(),
            'tournament' => $tournament,
        ]);
    }
}