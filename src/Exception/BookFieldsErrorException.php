<?php

namespace App\Exception;

use RuntimeException;

class BookFieldsErrorException extends RuntimeException
{
    public function __construct() {
        parent::__construct('fields are incorrect');
    }
}