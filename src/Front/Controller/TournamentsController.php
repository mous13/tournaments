<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Front\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/tournaments', 'tournaments')]
class TournamentsController extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Route('/list', name: '_list')]
    public function list(): Response
    {
        return $this->render('', [
        ]);
    }
}