<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PreDeleteEvent extends Event
{
    public const NAME = 'preDelete';
    protected int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}