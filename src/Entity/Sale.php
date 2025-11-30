<?php

declare(strict_types=1);

namespace App\Entity;

final readonly class Sale
{
    public function __construct(
        public int $id,

        public \DateTimeInterface $date,

        public int $amount,

        public User $user,
    ) {
    }
}
