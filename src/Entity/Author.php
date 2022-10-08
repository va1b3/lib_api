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
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "authors")]
    private Collection $books;

    #[ORM\Column(type: "integer")]
    private int $booksNumber;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
     *
     * @return $this
     */
    public function setBooks(Collection $books): self
    {
        $this->books = $books;

        return $this;
    }

    public function getBooksNumber(): int
    {
        return $this->booksNumber;
    }

    public function setBooksNumber(int $booksNumber): self
    {
        $this->booksNumber = $booksNumber;

        return $this;
    }
}