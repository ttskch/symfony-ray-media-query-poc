<?php

declare(strict_types=1);

namespace App\Entity;

final readonly class UserTeamHistory
{
    public function __construct(
        public int $id,

        public User $user,

        public Team $team,

        public ?\DateTimeInterface $fromDate = null,

        public ?\DateTimeInterface $toDate = null,
    ) {
    }
}
