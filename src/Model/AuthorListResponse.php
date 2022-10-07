<?php

namespace App\Model;

class AuthorListResponse
{
    /**
     * @var AuthorItem[]
     */
    private array $items;

    /**
     * @param AuthorItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return AuthorItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}