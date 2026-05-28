<?php

namespace App\Controller;

use App\Application\Review\CreateReviewHandler;
use App\Application\Review\DTO\CreateReviewDTO;
use App\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewController extends AbstractController
{
    #[Route('/', name: 'app_reviews')]
    public function index(
        Request $request,
        CreateReviewHandler $createReviewHandler
    ): Response
    {
        $dto = new CreateReviewDTO();

        $form = $this->createForm(ReviewType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createReviewHandler($dto);

            return $this->redirectToRoute('app_reviews');
        }

        return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
            'review_form' => $form->createView(),
        ]);
    }
}
