<?php

namespace App\Model;

class ErrorResponse
{
    public function __construct(private readonly string $code,
                                private readonly string $message
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function serialize(): array
    {
        return ['code' => $this->getCode(), 'message' => $this->getMessage()];
    }
}