<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $title;

    /**
     * @var Collection<Author>
     */
    #[ORM\ManyToMany(targetEntity: Author::class)]
    private Collection $authors;

    #[ORM\Column(type: "string")]
    private string $description;

    #[ORM\Column(type: "integer")]
    private int $year;

    #[ORM\Column(type: "string")]
    private string $image;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return Collection<Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    /**
     * @param Collection<Author> $authors
     */
    public function setAuthors(Collection $authors): void
    {
        $this->authors = $authors;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear($year): void
    {
        $this->year = $year;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }
}