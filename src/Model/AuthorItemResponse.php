<?php

namespace App\Model;

class AuthorItemResponse
{
    private AuthorItem $item;

    public function __construct(AuthorItem $item)
    {
        $this->item = $item;
    }

    public function getItem(): AuthorItem
    {
        return $this->item;
    }
}