<?php

namespace App\Model;

class BookListResponse
{
    /**
     * @var BookItem[]
     */
    private array $items;

    /**
     * @param BookItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return BookItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}