<?php

declare(strict_types=1);

namespace App\Application\Review\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateReviewDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $companyName;

    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 5)]
    public int $rating;

    public ?string $reviewText;

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $authorEmail;
}
