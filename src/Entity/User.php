<?php

declare(strict_types=1);

namespace App\Entity;

final readonly class User implements \Stringable
{
    public function __construct(
        public int $id,

        public string $username,

        /**
         * @var array<UserTeamHistory>
         */
        public array $teamHistories = [],
    ) {
    }

    public function getTeam(\DateTimeInterface $date): ?Team
    {
        foreach ($this->teamHistories as $history) {
            if (
                ($history->fromDate === null || $history->fromDate <= $date)
                && ($history->toDate === null || $history->toDate >= $date)
            ) {
                return $history->team;
            }
        }

        return null;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
