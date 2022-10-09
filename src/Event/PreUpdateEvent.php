<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PreUpdateEvent extends Event
{
    public const NAME = 'preUpdate';
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