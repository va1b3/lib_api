<?php

namespace App\Model;

class AuthorItemResponse
{
    private int $id;
    private string $name;
    private array $books;
    private int $booksCount;

    public function __construct(
        int $id,
        string $name,
        array $books,
        int $booksCount
    ) {
        $this->id         = $id;
        $this->name       = $name;
        $this->books      = $books;
        $this->booksCount = $booksCount;
    }

    public function serialize(): array
    {
        return [
            'id'          => $this->getId(),
            'name'        => $this->getName(),
            'books'       => $this->getBooks(),
            'books_count' => $this->getBooksCount()
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBooks(): array
    {
        return $this->books;
    }

    public function getBooksCount(): int
    {
        return $this->booksCount;
    }
}