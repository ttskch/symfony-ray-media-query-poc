<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251130181523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        for ($i = 1; $i <= 50; ++$i) {
            $username = "user{$i}";
            $this->addSql('INSERT INTO user (id, username) VALUES (?, ?)', [$i, $username]);
        }

        for ($i = 1; $i <= 10; ++$i) {
            $name = "team{$i}";
            $this->addSql('INSERT INTO team (id, name) VALUES (?, ?)', [$i, $name]);
        }

        for ($id = 1, $i = 1; $i <= 50; ++$i) {
            $fromDate = new \DateTimeImmutable('2025-01-01');
            $rand = rand(1, 5);
            for ($j = 1; $j <= $rand; ++$j) { // 1-5 team histories per user
                $toDate = (clone $fromDate)->add(new \DateInterval(sprintf('P%dD', rand(1, 5)))); // 1-5 days between each history
                $this->addSql('INSERT INTO user_team_history (id, user_id, team_id, from_date, to_date) VALUES (?, ?, ?, ?, ?)', [
                    $id++,
                    $i,
                    rand(1, 10),
                    $j === 1 ? null : $fromDate->format('Y-m-d'),
                    $j === $rand ? null : $toDate->format('Y-m-d'),
                ]);
                $fromDate = $toDate->add(new \DateInterval('P1D'));
            }
        }

        for ($i = 1; $i <= 1000; ++$i) {
            $date = new \DateTimeImmutable(sprintf('2025-01-%02d', rand(1, 31)));
            $amount = rand(10, 1000) * 1000;
            $userId = rand(1, 50);
            $this->addSql('INSERT INTO sale (id, date, amount, user_id) VALUES (?, ?, ?, ?)', [
                $i,
                $date->format('Y-m-d'),
                $amount,
                $userId,
            ]);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM user');
        $this->addSql('DELETE FROM team');
        $this->addSql('DELETE FROM user_team_history');
        $this->addSql('DELETE FROM sale');
    }
}
