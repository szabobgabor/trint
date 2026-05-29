<?php

namespace App\Controller;

use App\Application\CompanyStats\GetCompanyStatsHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompaniesController extends AbstractController
{
    public function __construct(
        private readonly GetCompanyStatsHandler $getCompanyStatsHandler,
    )
    {
    }
    #[Route('/companies', name: 'app_company_stats')]
    public function index(): Response
    {
        return $this->render('companies/index.html.twig', [
            'controller_name' => 'CompaniesController',
            'companyStats' => ($this->getCompanyStatsHandler)()
        ]);
    }
}
