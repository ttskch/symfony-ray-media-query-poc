<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251130181334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sale (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, date DATE NOT NULL, amount INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_E54BC005A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E54BC005A76ED395 ON sale (user_id)');
        $this->addSql('CREATE TABLE team (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user_team_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, from_date DATE DEFAULT NULL, to_date DATE DEFAULT NULL, user_id INTEGER NOT NULL, team_id INTEGER NOT NULL, CONSTRAINT FK_580F7D7EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_580F7D7E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_580F7D7EA76ED395 ON user_team_history (user_id)');
        $this->addSql('CREATE INDEX IDX_580F7D7E296CD8AE ON user_team_history (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_team_history');
    }
}
