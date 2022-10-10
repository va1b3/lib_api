<?php

namespace App\Model;

class BookItem
{
    private int $id;
    private string $title;
    private int $year;

    public function __construct(
        int $id,
        string $title,
        int $year
    ) {
        $this->id    = $id;
        $this->title = $title;
        $this->year  = $year;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'year' => $this->getYear()
        ];
    }
}