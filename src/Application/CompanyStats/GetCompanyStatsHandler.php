<?php

declare(strict_types=1);

namespace App\Application\CompanyStats;

use App\Application\CompanyStats\DTO\CompanyStatDTO;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

readonly class GetCompanyStatsHandler
{
    public function __construct(
        private Connection $connection,
    )
    {}

    /**
     * @return CompanyStatDTO[]
     * @throws Exception
     */
    public function __invoke(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('c.name as company_name, COUNT(company_id) as reviews_count, AVG(rating) as avg_rating')
            ->from('review', 'r')
            ->innerJoin('r', 'company', 'c', 'r.company_id = c.id')
            ->groupBy('c.id')
            ->addGroupBy('c.name');

        $rows = $qb->executeQuery()->fetchAllAssociative();

        return array_map(
            fn(array $row) => new CompanyStatDTO(
                $row['company_name'],
                (float) $row['avg_rating'],
                (int) $row['reviews_count'],
            ),
            $rows
        );
    }
}
