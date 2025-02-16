<?php

declare(strict_types=1);

namespace Vanguard\Tournaments\Admin\MenuBuilder;

use Forumify\Admin\MenuBuilder\AdminMenuBuilderInterface;
use Forumify\Core\MenuBuilder\Menu;
use Forumify\Core\MenuBuilder\MenuItem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuBuilder implements AdminMenuBuilderInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    public function build(Menu $menu): void
    {
        $url = $this->urlGenerator->generate(...);


        $TournamentsMenu = new Menu('Tournaments', ['icon' => 'ph ph-tree-structure', 'permission' => 'tournaments.admin.manage'], [
            new MenuItem('Categories', $url('tournaments_admin_tournaments_list'), ['icon' => 'ph ph-book-open', 'permission' => 'tournaments.admin.manage']),
        ]);


        $menu->addItem($TournamentsMenu);
    }
}