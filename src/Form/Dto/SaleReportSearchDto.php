<?php

declare(strict_types=1);

namespace App\Form\Dto;

use App\Entity\Team;
use Symfony\Component\Validator\Constraints as Assert;

final class SaleReportSearchDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Range(min: '2025-01-01', max: '2025-01-31')]
        public ?\DateTimeInterface $date = new \DateTimeImmutable('2025-01-01'),

        public ?Team $team = null,
    ) {
    }
}
