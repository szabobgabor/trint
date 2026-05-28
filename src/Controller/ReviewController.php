<?php

namespace App\Controller;

use App\Application\Review\CreateReviewHandler;
use App\Application\Review\DTO\CreateReviewDTO;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewController extends AbstractController
{
    #[Route('/', name: 'app_reviews')]
    public function index(
        Request $request,
        CreateReviewHandler $createReviewHandler,
        ReviewRepository $reviewRepository
    ): Response
    {
        $dto = new CreateReviewDTO();

        $form = $this->createForm(ReviewType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createReviewHandler($dto);

            return $this->redirectToRoute('app_reviews');
        }

        $cursor = $request->query->has('cursor') ? $request->query->getInt('cursor') : null;
        $reviews = $reviewRepository->listReviews($cursor);
        $lastId = count($reviews) ? end($reviews)->getId() : null;

        return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
            'review_form' => $form->createView(),
            'reviews' => $reviews,
            'cursor' => $cursor,
            'lastId' => $lastId,
        ]);
    }

    #[Route('/review/{id}', name: 'app_review')]
    public function review(
        Request $request,
        ReviewRepository $reviewRepository
    ): Response
    {
        $id = $request->attributes->getInt('id');
        $review = $reviewRepository->find($id);
        return $this->render('review/review.html.twig', [
            'review' => $review,
        ]);
    }
}
