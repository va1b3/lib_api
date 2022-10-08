<?php

namespace App\Service;

use App\Model\AuthorItemResponse;
use App\Model\AuthorListResponse;
use App\Repository\AuthorRepository;

class AuthorService
{
    public function __construct(private AuthorRepository $authorRepository)
    {
    }

    public function getAuthor(int $id): array
    {
        if ( ! $this->authorRepository->isExistBy(['id' => $id])) {
            return ['success' => false, 'error' => 'author doesn\'t exist'];
        }

        $author = $this->authorRepository->findOneBy(['id' => $id]);
        $books  = array();
        foreach ($author->getBooks() as $book) {
            $books[] = [
                'id'    => $book->getId(),
                'title' => $book->getTitle(),
                'year'  => $book->getYear()
            ];
        }

        return (new AuthorItemResponse(
            $author->getId(),
            $author->getName(),
            $books,
            $author->getBooksNumber()))->serialize();
    }

    public function getListAuthor(?int $fromBooks, ?int $toBooks): array
    {
        $authors      = $this->authorRepository->findAll();
        $authorsArray = array();
        foreach ($authors as $author) {
            if ($author->getBooksNumber() >= ($fromBooks ?? 1)
                and $author->getBooksNumber()
                    <= ($toBooks ?? $author->getBooksNumber())
            ) {
                $booksArray = array();
                foreach ($author->getBooks() as $book) {
                    $booksArray[] = [
                        'id'    => $book->getId(),
                        'title' => $book->getTitle(),
                        'year'  => $book->getYear()
                    ];
                }
                $authorsArray[] = (new AuthorItemResponse(
                    $author->getId(),
                    $author->getName(),
                    $booksArray,
                    $author->getBooksNumber()))->serialize();
            }
        }

        return (new AuthorListResponse($authorsArray))->serialize();
    }
}