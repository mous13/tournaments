<?php

declare(strict_types=1);

namespace TournamentsDoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250220164622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'adds participant limits';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament ADD participant_limit INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament DROP participant_limit');
    }
}
