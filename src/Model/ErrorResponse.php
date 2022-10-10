<?php

namespace App\Model;

class ErrorResponse
{
    public function __construct(private string $error)
    {
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function serialize(): array
    {
        return ['error' => $this->error];
    }
}