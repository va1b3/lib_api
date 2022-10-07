<?php

namespace App\Model;

class BookItemResponse
{
    private BookItem $item;

    public function __construct(BookItem $item)
    {
        $this->item = $item;
    }

    public function getItem(): BookItem
    {
        return $this->item;
    }
}