<?php

declare(strict_types=1);

namespace TournamentsDoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250221132424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added bidirectional relations between matches ';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD previous_match_one_id INT DEFAULT NULL, ADD previous_match_two_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD CONSTRAINT FK_6BCD8823F720755F FOREIGN KEY (previous_match_one_id) REFERENCES tournaments_tournament_matches (id)');
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD CONSTRAINT FK_6BCD88239C7C9290 FOREIGN KEY (previous_match_two_id) REFERENCES tournaments_tournament_matches (id)');
        $this->addSql('CREATE INDEX IDX_6BCD8823F720755F ON tournaments_tournament_matches (previous_match_one_id)');
        $this->addSql('CREATE INDEX IDX_6BCD88239C7C9290 ON tournaments_tournament_matches (previous_match_two_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP FOREIGN KEY FK_6BCD8823F720755F');
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP FOREIGN KEY FK_6BCD88239C7C9290');
        $this->addSql('DROP INDEX IDX_6BCD8823F720755F ON tournaments_tournament_matches');
        $this->addSql('DROP INDEX IDX_6BCD88239C7C9290 ON tournaments_tournament_matches');
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP previous_match_one_id, DROP previous_match_two_id');
    }
}
