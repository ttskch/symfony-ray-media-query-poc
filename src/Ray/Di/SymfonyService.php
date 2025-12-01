<?php

declare(strict_types=1);

namespace App\Ray\Di;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
final readonly class SymfonyService
{
    public function __construct(
        public string $serviceId,
    ) {
    }
}
