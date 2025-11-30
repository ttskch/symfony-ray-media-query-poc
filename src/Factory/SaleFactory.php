<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Sale;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserTeamHistory;

final readonly class SaleFactory
{
    public function factory(int $id, string $date, int $amount, string $user_json): Sale
    {
        $userArray = json_decode($user_json, true);

        $teamHistories = isset($userArray['team_histories'])
            ? array_map(fn (array $history) => new UserTeamHistory(
                id: $history['id'],
                user: new User(id: $history['user_id'], username: ''),
                team: isset($history['team_json'])
                    ? new Team(id: $history['team_json']['id'], name: $history['team_json']['name'])
                    : new Team(id: $history['team_id'], name: ''),
                fromDate: $history['from_date'] ? new \DateTimeImmutable($history['from_date']) : null,
                toDate: $history['to_date'] ? new \DateTimeImmutable($history['to_date']) : null,
            ), $userArray['team_histories'])
            : []
        ;

        $user = new User(
            id: $userArray['id'],
            username: $userArray['username'],
            teamHistories: $teamHistories,
        );

        return new Sale(
            id: $id,
            date: new \DateTimeImmutable($date),
            amount: $amount,
            user: $user,
        );
    }
}
