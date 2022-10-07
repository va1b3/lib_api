<?php

namespace App\Service;

use App\Repository\BookRepository;

class BookService
{
    public function __construct(private BookRepository $book_repository)
    {
    }
}