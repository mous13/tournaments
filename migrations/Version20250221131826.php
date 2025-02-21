<?php

declare(strict_types=1);

namespace TournamentsDoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20250221131826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'adds match positions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD position INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP position');
    }
}
