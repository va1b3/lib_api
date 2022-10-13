<?php

namespace App\Exception;

use RuntimeException;

class AuthorNotFoundException extends RuntimeException
{
    public function __construct() {
        parent::__construct('Author not found');
    }
}