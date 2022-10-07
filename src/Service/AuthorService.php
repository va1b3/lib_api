<?php

namespace App\Service;

use App\Repository\AuthorRepository;

class AuthorService
{
    public function __construct(private AuthorRepository $author_repository)
    {
    }
}