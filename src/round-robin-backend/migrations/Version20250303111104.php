<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303111104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE team (id SERIAL NOT NULL, name VARCHAR(128) NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_c4e0a61f5e237e06 ON team (name)');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE tournament (id SERIAL NOT NULL, name VARCHAR(128) NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_bd5fb8d95e237e06 ON tournament (name)');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE game (id SERIAL NOT NULL, tournament_id INT NOT NULL, round INT NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_232b318c33d1a3e7 ON game (tournament_id)');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('CREATE TABLE team_score (id SERIAL NOT NULL, game_id INT NOT NULL, team_id INT NOT NULL, tournament_id INT NOT NULL, score INT NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f59f7e1133d1a3e7 ON team_score (tournament_id)');
        $this->addSql('CREATE INDEX idx_f59f7e11e48fd905 ON team_score (game_id)');
        $this->addSql('CREATE INDEX idx_f59f7e11296cd8ae ON team_score (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE team');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE tournament');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE game');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL120Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL120Platform'."
        );

        $this->addSql('DROP TABLE team_score');
    }
}
