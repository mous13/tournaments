<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Front\Controller;

use Forumify\Core\Service\MediaService;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vanguard\Tournaments\Core\Entity\Tournament;
use Vanguard\Tournaments\Core\Entity\TournamentParticipant;
use Vanguard\Tournaments\Core\Repository\TournamentMatchRepository;
use Vanguard\Tournaments\Core\Repository\TournamentParticipantRepository;
use Vanguard\Tournaments\Core\Repository\TournamentRepository;
use Vanguard\Tournaments\Core\Service\BracketGenerator;
use Vanguard\Tournaments\Front\Form\TournamentMatchScoreType;
use Vanguard\Tournaments\Front\Form\TournamentType;

#[Route('/tournaments', name: 'tournaments')]
class TournamentController extends AbstractController
{

    private const STATUS = [
        'preparing'   => 'Preparing',
        'in_progress' => 'In Progress',
        'finished'    => 'Finished',
    ];
    public function __construct(
        private readonly MediaService $mediaService,
        private readonly FilesystemOperator $logoStorage,
        private readonly TournamentRepository $tournamentRepository,
        private readonly Security $security,
        private readonly TournamentParticipantRepository $tournamentParticipantRepository,
        private readonly BracketGenerator $bracketGenerator,
        private readonly TournamentMatchRepository $tournamentMatchRepository

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
            $tournament->setStatus('Preparing');

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

    #[Route('/{id}', name: '_tournament')]
    public function viewTournament(Request $request, int $id): Response
    {
        $tournament = $this->tournamentRepository->find($id);

        return $this->render('@VanguardTournaments/front/tournaments/tournament.html.twig', [

            'tournament' => $tournament,
        ]);
    }

    #[Route('/{id}/join', name: '_join')]
    public function joinTournament(Request $request, int $id): Response
    {
        $tournament = $this->tournamentRepository->find($id);
        $user = $this->security->getUser();

        if (count($tournament->getParticipants()) >= $tournament->getParticipantLimit()) {
            $this->addFlash('error', 'Tournament is full.');
            return $this->redirectToRoute('tournaments_tournaments_tournament', ['id' => $id]);
        }

        foreach ($tournament->getParticipants() as $participant) {
            if ($participant->getUser()->getId() === $user->getId()) {
                $this->addFlash('error', 'You are already registered for this tournament.');
                return $this->redirectToRoute('tournaments_tournaments_tournament', ['id' => $id]);
            }
        }

        $participant = new TournamentParticipant();
        $participant->setTournament($tournament);
        $participant->setUser($user);

        $this->tournamentParticipantRepository->save($participant);



        $this->addFlash('success', 'You have successfully joined the tournament.');
        return $this->redirectToRoute('tournaments_tournaments_tournament', ['id' => $id]);
    }

    #[Route('/{id}/generate', name: '_generate')]
    public function generateBracket(Request $request, int $id): Response
    {
        $tournament = $this->tournamentRepository->find($id);
        $user = $this->security->getUser();

        $existingMatches = $this->tournamentMatchRepository->findBy([
            'tournament' => $tournament,
            'round' => 1,
        ]);
        foreach ($existingMatches as $existingMatch) {
            $this->tournamentMatchRepository->remove($existingMatch);
        }
        $this->bracketGenerator->generateCompleteBracket($tournament);

        return $this->redirectToRoute('tournaments_tournaments_tournament', ['id' => $id]);
    }

    #[Route('/match/{id}/update', name: '_update')]
    public function updateMatchScore(Request $request, int $id): Response
    {
        $match = $this->tournamentMatchRepository->find($id);
        $form = $this->createForm(TournamentMatchScoreType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scoreOne = $form->get('scoreOne')->getData();
            $scoreTwo = $form->get('scoreTwo')->getData();

            $oldWinner = $match->getWinner();


            if ($scoreOne > $scoreTwo) {
                $newWinner = $match->getParticipantOne();
            } elseif ($scoreOne < $scoreTwo) {
                $newWinner = $match->getParticipantTwo();
            } else {
                $newWinner = null;
            }

            if ($oldWinner !== $newWinner && $oldWinner !== null) {
                $this->bracketGenerator->cascadeClear($match, $oldWinner);
            }

            $match->setWinner($newWinner);

            $this->tournamentMatchRepository->save($match);

            if ($newWinner) {
                $this->bracketGenerator->advanceWinner($match);
            }

            $this->addFlash('success', 'Match score updated!');
            return $this->redirectToRoute('tournaments_tournaments_tournament', ['id' => $match->getTournament()->getId()]);
        }

        return $this->render('@VanguardTournaments/front/matches/update.html.twig', [
            'form' => $form->createView(),
            'match' => $match,
        ]);
    }
}