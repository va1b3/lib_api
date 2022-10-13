<?php

namespace App\Exception;

use RuntimeException;

class FieldsErrorException extends RuntimeException
{
    public function __construct() {
        parent::__construct('Incorrect fields');
    }
}