<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PreAddEvent extends Event
{
    public const NAME = 'preAdd';
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}