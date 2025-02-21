<?php

namespace Vanguard\Tournaments\Core\Service;

use Vanguard\Tournaments\Core\Entity\Tournament;
use Vanguard\Tournaments\Core\Entity\TournamentMatch;
use Vanguard\Tournaments\Core\Repository\TournamentMatchRepository;

class BracketGenerator
{
    public function __construct(
        private TournamentMatchRepository $matchRepository
    ) {}
    
    public function generateRoundOneMatches(Tournament $tournament): void
    {
        $participants = $tournament->getParticipants()->toArray();

        $matches = $this->matchRepository->findBy([
            'tournament' => $tournament,
            'round' => 1,
        ]);

        $assignedParticipantIds = [];
        foreach ($matches as $match) {
            if ($match->getParticipantOne()) {
                $assignedParticipantIds[] = $match->getParticipantOne()->getId();
            }
            if ($match->getParticipantTwo()) {
                $assignedParticipantIds[] = $match->getParticipantTwo()->getId();
            }
        }

        $unassigned = array_filter($participants, function ($participant) use ($assignedParticipantIds) {
            return !in_array($participant->getId(), $assignedParticipantIds);
        });
        $unassigned = array_values($unassigned);

        $count = count($unassigned);
        for ($i = 0; $i < $count; $i += 2) {
            $match = new TournamentMatch();
            $match->setTournament($tournament);
            $match->setRound(1);
            $match->setParticipantOne($unassigned[$i]);

            if (isset($unassigned[$i + 1])) {
                $match->setParticipantTwo($unassigned[$i + 1]);
            } else {
                $match->setWinner($unassigned[$i]);
            }

            $this->matchRepository->save($match);
        }
    }

    public function generateCompleteBracket(Tournament $tournament): void
    {
        $participants = $tournament->getParticipants()->toArray();
        $numParticipants = count($participants);

        $totalRounds = (int) ceil(log($numParticipants, 2));

        $roundMatches = [];
        $numMatchesRound1 = (int) ceil($numParticipants / 2);
        for ($i = 0; $i < $numMatchesRound1; $i++) {
            $match = new TournamentMatch();
            $match->setTournament($tournament);
            $match->setRound(1);
            $match->setPosition($i);

            $participantOne = $participants[$i * 2] ?? null;
            $participantTwo = $participants[$i * 2 + 1] ?? null;
            $match->setParticipantOne($participantOne);
            $match->setParticipantTwo($participantTwo);

            if (!$participantTwo && $participantOne) {
                $match->setWinner($participantOne);
            }
            $this->matchRepository->save($match);
            $roundMatches[] = $match;
        }

        $previousRoundMatches = $roundMatches;
        for ($round = 2; $round <= $totalRounds; $round++) {
            $numMatches = (int) ceil(count($previousRoundMatches) / 2);
            $currentRoundMatches = [];
            for ($i = 0; $i < $numMatches; $i++) {
                $match = new TournamentMatch();
                $match->setTournament($tournament);
                $match->setRound($round);
                $match->setPosition($i);

                if (isset($previousRoundMatches[2 * $i])) {
                    $match->setPreviousMatchOne($previousRoundMatches[2 * $i]);
                }
                if (isset($previousRoundMatches[2 * $i + 1])) {
                    $match->setPreviousMatchTwo($previousRoundMatches[2 * $i + 1]);
                }
                $this->matchRepository->save($match);
                $currentRoundMatches[] = $match;
            }
            $previousRoundMatches = $currentRoundMatches;
        }
    }

    public function advanceWinner(TournamentMatch $match): void
    {
        if (!$match->getWinner()) {
            return;
        }

        $currentRound = $match->getRound();
        $nextRound = $currentRound + 1;
        $currentPosition = $match->getPosition();
        $nextMatchPosition = (int) floor($currentPosition / 2);

        $nextMatch = $this->matchRepository->findOneBy([
            'tournament' => $match->getTournament(),
            'round'      => $nextRound,
            'position'   => $nextMatchPosition,
        ]);

        if (!$nextMatch) {
            return;
        }

        if ($currentPosition % 2 === 0) {
            $nextMatch->setParticipantOne($match->getWinner());
        } else {
            $nextMatch->setParticipantTwo($match->getWinner());
        }

        $this->matchRepository->save($nextMatch);
    }

    public function cascadeClear(TournamentMatch $match, ?object $oldWinner): void
    {
        $currentRound = $match->getRound();
        $nextRound = $currentRound + 1;
        $nextMatchPosition = (int) floor($match->getPosition() / 2);
        $nextMatch = $this->matchRepository->findOneBy([
            'tournament' => $match->getTournament(),
            'round'      => $nextRound,
            'position'   => $nextMatchPosition,
        ]);
        if (!$nextMatch) {
            return;
        }
        if ($match->getPosition() % 2 === 0) {
            if ($nextMatch->getParticipantOne() && $nextMatch->getParticipantOne()->getId() === $oldWinner?->getId()) {
                $nextMatch->setParticipantOne(null);
                $this->matchRepository->save($nextMatch);
                $this->cascadeClear($nextMatch, $oldWinner);
            }
        } else {
            if ($nextMatch->getParticipantTwo() && $nextMatch->getParticipantTwo()->getId() === $oldWinner?->getId()) {
                $nextMatch->setParticipantTwo(null);
                $this->matchRepository->save($nextMatch);
                $this->cascadeClear($nextMatch, $oldWinner);
            }
        }
    }
}

