<?php

namespace App\Model;

class BookListResponse
{
    /**
     * @var BookItemResponse[]
     */
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function serialize(): array
    {
        $serialized = array();
        foreach ($this->items as $item) {
            $serialized['items'][] = $item;
        }

        return $serialized;
    }
}