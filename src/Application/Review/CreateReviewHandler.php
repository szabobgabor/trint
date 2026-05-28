<?php

declare(strict_types=1);

namespace App\Application\Review;

use App\Application\Review\DTO\CreateReviewDTO;
use App\Entity\Company;
use App\Entity\Review;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateReviewHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyRepository $companyRepository,
    )
    {}
    public function __invoke(CreateReviewDTO $dto): void
    {
        $company = $this->companyRepository->findOneByName($dto->companyName);
        if ($company === null) {
            $company = new Company();
            $company->setName($dto->companyName);
            $this->entityManager->persist($company);
        }

        $review = new Review()
            ->setCompany($company)
            ->setRating($dto->rating)
            ->setReviewText($dto->reviewText)
            ->setAuthorEmail($dto->authorEmail);

        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }
}
