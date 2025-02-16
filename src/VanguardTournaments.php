<?php

declare(strict_types=1);

namespace Vanguard\Tournaments;

use Forumify\Plugin\AbstractForumifyPlugin;
use Forumify\Plugin\PluginMetadata;
use Vanguard\Core\Service\AddonInterface;

class VanguardTournaments extends AbstractForumifyPlugin
{
    public function getPluginMetadata(): PluginMetadata
    {
        return new PluginMetadata(
            'Tournaments',
            'Vanguard',
        );
    }

    public function getPermissions(): array
    {
        return [
            'admin' => ['manage']
        ];

    }


}