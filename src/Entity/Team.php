<?php

declare(strict_types=1);

namespace App\Entity;

final readonly class Team implements \Stringable
{
    public function __construct(
        public int $id,

        public string $name,
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
