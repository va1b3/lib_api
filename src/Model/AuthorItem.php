<?php

namespace App\Model;

class AuthorItem
{
    private int $id;
    private string $name;
    private int $books_number;

    public function __construct(int $id, string $name, int $books_number)
    {
        $this->id = $id;
        $this->name = $name;
        $this->books_number = $books_number;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBooksNumber(): int
    {
        return $this->books_number;
    }
}