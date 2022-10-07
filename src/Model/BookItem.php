<?php

namespace App\Model;

class BookItem
{
    private int $id;
    private string $title;
    private string $description;
    private int $year;
    private string $image;

    public function __construct(
        int $id,
        string $title,
        string $description,
        int $year,
        string $image = '',
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->year = $year;
        $this->image = $image;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}