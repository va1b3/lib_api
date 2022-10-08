<?php

namespace App\Model;

class BookItemResponse
{
    private int $id;
    private string $title;
    private array $authors;
    private string $description;
    private int $year;
    private string $image;

    public function __construct(
        int $id,
        string $title,
        array $authors,
        string $description,
        int $year,
        string $image = '',
    ) {
        $this->id          = $id;
        $this->title       = $title;
        $this->authors     = $authors;
        $this->description = $description;
        $this->year        = $year;
        $this->image       = $image;
    }

    public function serialize(): array
    {
        return [
            'id'          => $this->getId(),
            'title'       => $this->getTitle(),
            'authors'     => $this->getAuthors(),
            'description' => $this->getDescription(),
            'year'        => $this->getYear(),
            'image'       => $this->getImage()
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthors(): array
    {
        return $this->authors;
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