<?php

declare(strict_types=1);

namespace App\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class SaleDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Range(min: '2025-01-01', max: '2025-01-31')]
        public ?\DateTimeInterface $date = null,

        #[Assert\NotBlank]
        public ?int $amount = null,

        #[Assert\NotBlank]
        public ?int $userId = null,
    ) {
    }
}
