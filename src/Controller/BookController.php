<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private BookService $bookService)
    {
    }

    #[Route(path: '/api/v1/add', methods: ['POST'])]
    public function addBook(Request $request): Response
    {
        return $this->json($this->bookService
            ->add(json_decode($request->getContent(), true)));
    }

    #[Route(path: '/api/v1/update', methods: ['PATCH'])]
    public function updateBook(Request $request): Response
    {
        return $this->json($this->bookService
            ->update(json_decode($request->getContent(), true)));
    }

    #[Route(path: '/api/v1/delete/{id}', methods: ['GET'])]
    public function deleteBook(int $id): Response
    {
        return $this->json($this->bookService->delete($id));
    }

    #[Route(path: '/api/v1/get-book/{id}', methods: ['GET'])]
    public function getBook(int $id): JsonResponse
    {
        return $this->json($this->bookService->getBook($id));
    }

    #[Route(path: '/api/v1/get-books-list', methods: ['GET'])]
    public function getListBook(Request $request): JsonResponse
    {
        return $this->json($this->bookService->getListBook(
            $request->get('from_authors'),
            $request->get('to_authors'),
            $request->get('from_year'),
            $request->get('to_year')));
    }

    #[Route(path: '/api/v1/get-books-list-authors-dqb', methods: ['GET'])]
    public function getListBookAuthorsFilterDqb(): JsonResponse
    {
        return $this->json($this->bookService->getListBookAuthorsFilterDqb());
    }

    #[Route(path: '/api/v1/get-books-list-authors-sql', methods: ['GET'])]
    public function getListBookAuthorsFilterSql(): JsonResponse
    {
        return $this->json($this->bookService->getListBookAuthorsFilterSql());
    }
}