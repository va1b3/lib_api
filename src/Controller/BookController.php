<?php

namespace App\Controller;

use App\Model\BookItemResponse;
use App\Model\BookListResponse;
use App\Model\ErrorResponse;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: book was added successfully",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="sucess",
     *                     type="bool"
     *                 ),
     *                 example={"sucess": true}
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns 400: book fields error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=409,
     *     description="Returns 409: book already exist",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(
     *     required=true,
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title", "authors", "description", "year"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="authors",
     *                     type="array",
     *                     @OA\Items(
     *                          type="string"
     *                      )
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="year",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 example={
     *                      "title": "example_title",
     *                      "authors": {"author_1", "author_2"},
     *                      "description": "example_description",
     *                      "year": 2022,
     *                      "image": "https://images.com/image.png"
     *                 }
     *             )
     *         )
     *     }
     * )
     */
    #[Route(path: '/api/v1/add', methods: ['POST'])]
    public function addBook(Request $request): Response
    {
        return $this->json($this->bookService
            ->add(json_decode($request->getContent(), true)));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: book was updated successfully",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="sucess",
     *                     type="bool"
     *                 ),
     *                 example={"sucess": true}
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns 400: book fields error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returns 404: book not found",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(
     *     required=true,
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"id", "title", "authors", "description", "year"},
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="authors",
     *                     type="array",
     *                     @OA\Items(
     *                          type="string"
     *                      )
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="year",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 example={
     *                      "id": 1,
     *                      "title": "example_title",
     *                      "authors": {"author_1", "author_2"},
     *                      "description": "example_description",
     *                      "year": 2022,
     *                      "image": "https://images.com/image.png"
     *                 }
     *             )
     *         )
     *     }
     * )
     */
    #[Route(path: '/api/v1/update', methods: ['PATCH'])]
    public function updateBook(Request $request): Response
    {
        return $this->json($this->bookService
            ->update(json_decode($request->getContent(), true)));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: book was deleted successfully",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="sucess",
     *                     type="bool"
     *                 ),
     *                 example={"sucess": true}
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returns 404: book not found",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/delete/{id}', methods: ['DELETE'])]
    public function deleteBook(int $id): Response
    {
        return $this->json($this->bookService->delete($id));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: book item response",
     *     @Model(type=BookItemResponse::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="Returns 404: book not found",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/get-book/{id}', methods: ['GET'])]
    public function getBook(int $id): JsonResponse
    {
        return $this->json($this->bookService->getBook($id));
    }

    /**
     * @OA\Parameter(
     *     name="from_authors",
     *     description="filter from book authors amount",
     *     required=false,
     *     in="query"
     * )
     * @OA\Parameter(
     *     name="to_authors",
     *     description="filter to book authors amount",
     *     required=false,
     *     in="query"
     * )
     * @OA\Parameter(
     *     name="from_year",
     *     description="filter from book year",
     *     required=false,
     *     in="query"
     * )
     * @OA\Parameter(
     *     name="to_year",
     *     description="filter to book year",
     *     required=false,
     *     in="query"
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: books list response",
     *     @Model(type=BookListResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/get-books-list', methods: ['GET'])]
    public function getListBook(Request $request): JsonResponse
    {
        return $this->json($this->bookService->getListBook(
            $request->get('from_authors'),
            $request->get('to_authors'),
            $request->get('from_year'),
            $request->get('to_year')));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: books list response",
     *     @Model(type=BookListResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/get-books-list-authors-filter-dqb', methods: ['GET'])]
    public function getListBookAuthorsFilterDqb(): JsonResponse
    {
        return $this->json($this->bookService->getListBookAuthorsFilterDqb());
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: books list response",
     *     @Model(type=BookListResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/get-books-list-authors-filter-sql', methods: ['GET'])]
    public function getListBookAuthorsFilterSql(): JsonResponse
    {
        return $this->json($this->bookService->getListBookAuthorsFilterSql());
    }
}