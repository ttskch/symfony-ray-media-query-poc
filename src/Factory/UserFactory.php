<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserTeamHistory;

final readonly class UserFactory
{
    public function factory(int $id, string $username, string $team_histories_json): User
    {
        $teamHistoriesArray = json_decode($team_histories_json, true);

        $teamHistories = array_map(fn (array $history) => new UserTeamHistory(
            id: $history['id'],
            user: new User(id: $history['user_id'], username: ''),
            team: new Team(id: $history['team_json']['id'], name: $history['team_json']['name']),
            fromDate: $history['from_date'] ? new \DateTimeImmutable($history['from_date']) : null,
            toDate: $history['to_date'] ? new \DateTimeImmutable($history['to_date']) : null,
        ), $teamHistoriesArray);

        return new User(
            id: $id,
            username: $username,
            teamHistories: $teamHistories,
        );
    }
}
