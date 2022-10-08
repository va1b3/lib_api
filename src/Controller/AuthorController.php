<?php

namespace App\Controller;

use App\Service\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    #[Route(path: '/api/v1/get-author/{id}', methods: ['GET'])]
    public function getAuthor(int $id): JsonResponse
    {
        return $this->json($this->authorService->getAuthor($id));
    }

    #[Route(path: '/api/v1/get-authors-list', methods: ['GET'])]
    public function getListAuthor(Request $request): JsonResponse
    {
        return $this->json($this->authorService->getListAuthor(
            $request->get('from_books'),
            $request->get('to_books')));
    }
}