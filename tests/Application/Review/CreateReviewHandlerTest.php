<?php

namespace App\Tests\Application\Review;

use App\Application\Review\CreateReviewHandler;
use App\Application\Review\DTO\CreateReviewDTO;
use App\Entity\Company;
use App\Entity\Review;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateReviewHandlerTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;

    private CompanyRepository&MockObject $companyRepository;

    private CreateReviewHandler $createReviewHandler;
    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->companyRepository = $this->createMock(CompanyRepository::class);
        $this->createReviewHandler = new CreateReviewHandler($this->entityManager, $this->companyRepository);
    }

    public function testExistingCompanyIsUsedForReview(): void
    {
       $dto = $this->createCreateReviewDTO();

        $company = new \App\Entity\Company()
            ->setName('test')
            ->setId(1);

        $this->companyRepository->expects($this->once())
            ->method('findOneByName')
            ->with($dto->companyName)
            ->willReturn($company);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->willReturnCallback(function (Review $review) use ($company) {
                $this->assertSame($company, $review->getCompany());
            });

        ($this->createReviewHandler)($dto);
    }

    public function testNonExistingCompanyIsCreatedForReview(): void
    {
        $dto = $this->createCreateReviewDTO();

        $this->companyRepository->expects($this->once())
            ->method('findOneByName')
            ->with($dto->companyName)
            ->willReturn(null);

        $this->entityManager->expects($this->exactly(2))
            ->method('persist')
            ->willReturnCallback(function (Review|Company $entity) use ($dto) {
                if ($entity instanceof Review) {
                    $this->assertSame($dto->companyName, $entity->getCompany()->getName());
                    $this->assertNull($entity->getCompany()->getId());
                }
            });
        ($this->createReviewHandler)($dto);
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testValuesAreMappedToEntity(): void
    {
        $dto = $this->createCreateReviewDTO();
        $this->entityManager->expects($this->atLeastOnce())
            ->method('persist')
            ->willReturnCallback(function (Review|Company $entity) use ($dto) {
                if ($entity instanceof Review) {
                    $this->assertSame($dto->companyName, $entity->getCompany()->getName());
                    $this->assertSame($dto->rating, $entity->getRating());
                    $this->assertSame($dto->reviewText, $entity->getReviewText());
                    $this->assertSame($dto->authorEmail, $entity->getAuthorEmail());
                }
            });
        ($this->createReviewHandler)($dto);
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testEntityIsPersisted(): void
    {
        $dto = $this->createCreateReviewDTO();

        $this->entityManager->expects($this->atLeastOnce())
            ->method('persist');
        $this->entityManager->expects($this->once())
            ->method('flush');

        ($this->createReviewHandler)($dto);
    }

    protected function createCreateReviewDTO(): CreateReviewDTO
    {
        $dto = new CreateReviewDTO();
        $dto->companyName = 'test';
        $dto->rating = 5;
        $dto->reviewText = 'test review text';
        $dto->authorEmail = 'testAuthor@example.com';
        return $dto;
    }
}
