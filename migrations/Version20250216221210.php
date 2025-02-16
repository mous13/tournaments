<?php

declare(strict_types=1);

namespace TournamentsDoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250216221210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'adds tournaments, matches, and participants';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE tournaments_tournament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, participant_type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, end_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournaments_tournament_matches (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, participant_one_id INT DEFAULT NULL, participant_two_id INT DEFAULT NULL, winner_id INT DEFAULT NULL, round INT NOT NULL, score_one INT DEFAULT NULL, score_two INT DEFAULT NULL, INDEX IDX_6BCD882333D1A3E7 (tournament_id), INDEX IDX_6BCD882349E67092 (participant_one_id), INDEX IDX_6BCD882322BA975D (participant_two_id), INDEX IDX_6BCD88235DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournaments_tournament_participants (id INT AUTO_INCREMENT NOT NULL, tournament_id INT NOT NULL, user_id INT NOT NULL, seed INT DEFAULT NULL, INDEX IDX_97B1262133D1A3E7 (tournament_id), INDEX IDX_97B12621A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD CONSTRAINT FK_6BCD882333D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments_tournament (id)');
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD CONSTRAINT FK_6BCD882349E67092 FOREIGN KEY (participant_one_id) REFERENCES tournaments_tournament_participants (id)');
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD CONSTRAINT FK_6BCD882322BA975D FOREIGN KEY (participant_two_id) REFERENCES tournaments_tournament_participants (id)');
        $this->addSql('ALTER TABLE tournaments_tournament_matches ADD CONSTRAINT FK_6BCD88235DFCD4B8 FOREIGN KEY (winner_id) REFERENCES tournaments_tournament_participants (id)');
        $this->addSql('ALTER TABLE tournaments_tournament_participants ADD CONSTRAINT FK_97B1262133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments_tournament (id)');
        $this->addSql('ALTER TABLE tournaments_tournament_participants ADD CONSTRAINT FK_97B12621A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP FOREIGN KEY FK_6BCD882333D1A3E7');
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP FOREIGN KEY FK_6BCD882349E67092');
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP FOREIGN KEY FK_6BCD882322BA975D');
        $this->addSql('ALTER TABLE tournaments_tournament_matches DROP FOREIGN KEY FK_6BCD88235DFCD4B8');
        $this->addSql('ALTER TABLE tournaments_tournament_participants DROP FOREIGN KEY FK_97B1262133D1A3E7');
        $this->addSql('ALTER TABLE tournaments_tournament_participants DROP FOREIGN KEY FK_97B12621A76ED395');
        $this->addSql('DROP TABLE tournaments_tournament');
        $this->addSql('DROP TABLE tournaments_tournament_matches');
        $this->addSql('DROP TABLE tournaments_tournament_participants');
    }
}
