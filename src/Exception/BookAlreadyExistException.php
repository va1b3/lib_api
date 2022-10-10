<?php

namespace App\Exception;

use RuntimeException;

class BookAlreadyExistException extends RuntimeException
{
    public function __construct() {
        parent::__construct('book already exist');
    }
}