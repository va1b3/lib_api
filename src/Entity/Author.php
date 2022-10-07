<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    /**
     * @var Collection<Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class)]
    private Collection $books;

    #[ORM\Column(type: "integer")]
    private int $books_number;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection<Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * @param Collection<Book> $books
     */
    public function setBooks(Collection $books): void
    {
        $this->books = $books;
    }

    public function getBooksNumber(): int
    {
        return $this->books_number;
    }

    public function setBooksNumber(int $books_number): void
    {
        $this->books_number = $books_number;
    }
}