<?php

namespace App\Controller;

use App\Model\AuthorItemResponse;
use App\Model\AuthorListResponse;
use App\Model\ErrorResponse;
use App\Service\AuthorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public function __construct(private readonly AuthorService $authorService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: author item response",
     *     @Model(type=AuthorItemResponse::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returns 404: author not found",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/get-author/{id}', methods: ['GET'])]
    public function getAuthor(int $id): JsonResponse
    {
        return $this->json($this->authorService->getAuthor($id));
    }

    /**
     * @OA\Parameter(
     *     name="from_books",
     *     description="filter from author books amount",
     *     required=false,
     *     in="query"
     * )
     * @OA\Parameter(
     *     name="to_books",
     *     description="filter to author books amount",
     *     required=false,
     *     in="query"
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: authors list response",
     *     @Model(type=AuthorListResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/get-authors-list', methods: ['GET'])]
    public function getListAuthor(Request $request): JsonResponse
    {
        return $this->json($this->authorService->getListAuthor(
            $request->get('from_books'),
            $request->get('to_books')));
    }
}