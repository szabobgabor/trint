<?php

declare(strict_types=1);

namespace App\Application\CompanyStats\DTO;

class CompanyStatDTO
{
    public function __construct(
        public readonly string $companyName,
        public readonly float $averageRating,
        public readonly int $numberOfReviews,
    )
    {}
}
