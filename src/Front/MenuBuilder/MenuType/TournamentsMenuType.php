<?php

namespace Vanguard\Tournaments\Front\MenuBuilder\MenuType;

use Forumify\Core\Entity\MenuItem;
use Forumify\Core\MenuBuilder\MenuType\AbstractMenuType;
use Twig\Environment;

class TournamentsMenuType extends AbstractMenuType
{
    public function __construct(
        private readonly Environment $twig,
    ){
    }

    public function getType(): string
    {
        return 'tournaments';
    }

    protected function render(MenuItem $item): string
    {
        return $this->twig->render('@VanguardTournaments/front/menu/tournaments.html.twig',[
            'name' => $item->getName(),
        ]);
    }

}