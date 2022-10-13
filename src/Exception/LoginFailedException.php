<?php

namespace App\Exception;

use RuntimeException;

class LoginFailedException extends RuntimeException
{
    public function __construct() {
        parent::__construct('Invalid credentials');
    }
}