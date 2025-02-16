<?php

declare(strict_types=1);

namespace TournamentsDoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250216224624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add image to tournaments';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament DROP image');
    }
}
