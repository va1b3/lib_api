<?php

namespace App\Service;

use App\Exception\AuthorNotFoundException;
use App\Model\AuthorItemResponse;
use App\Model\AuthorListResponse;
use App\Model\BookItem;
use App\Repository\AuthorRepository;

class AuthorService
{
    public function __construct(
        private readonly AuthorRepository $authorRepository
    ) {
    }

    public function getAuthor(int $id): array
    {
        if ( ! $this->authorRepository->isExistBy(['id' => $id])) {
            throw new AuthorNotFoundException();
        }

        $author = $this->authorRepository->findOneBy(['id' => $id]);
        $books  = array();
        foreach ($author->getBooks() as $book) {
            $books[] = (new BookItem($book->getId(), $book->getTitle(),
                $book->getYear()))->serialize();
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
                $books = array();
                foreach ($author->getBooks() as $book) {
                    $books[] = (new BookItem($book->getId(), $book->getTitle(),
                        $book->getYear()))->serialize();
                }
                $authorsArray[] = (new AuthorItemResponse(
                    $author->getId(),
                    $author->getName(),
                    $books,
                    $author->getBooksNumber()))->serialize();
            }
        }

        return (new AuthorListResponse($authorsArray))->serialize();
    }
}