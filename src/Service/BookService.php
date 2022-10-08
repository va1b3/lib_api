<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Model\BookItemResponse;
use App\Model\BookListResponse;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly AuthorRepository $authorRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function add(array $data): array
    {
        try {
            $title       = $data['title'];
            $authors     = $data['authors'];
            $description = $data['description'];
            $year        = $data['year'];
        } catch (Exception $exception) {
            return ['success' => false, 'error' => $exception->getMessage()];
        }

        if ($this->bookRepository->isExistBy(['title' => $title])) {
            return ['success' => false, 'error' => 'book already exist'];
        }

        $book = (new Book())
            ->setTitle($title)
            ->setDescription($description)
            ->setYear($year)
            ->setImage(($data['image']) ?? '');

        $book->setAuthors($this->updateBookAuthors($authors));
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return ['success' => true];
    }

    private function updateBookAuthors(array $authors): ArrayCollection
    {
        $authorsCollection = new ArrayCollection();
        foreach ($authors as $author) {
            if ( ! $this->authorRepository->isExistBy(['name' => $author])) {
                $newAuthor = (new Author())
                    ->setName($author)
                    ->setBooksNumber(1);
            } else {
                $newAuthor = $this->authorRepository
                    ->findOneBy(['name' => $author]);
            }

            $authorsCollection->add($newAuthor);
            $this->entityManager->persist($newAuthor);
        }

        return $authorsCollection;
    }

    public function update(array $data): array
    {
        try {
            $id          = $data['id'];
            $title       = $data['title'];
            $authors     = $data['authors'];
            $description = $data['description'];
            $year        = $data['year'];
        } catch (Exception $exception) {
            return ['success' => false, 'error' => $exception->getMessage()];
        }

        if ( ! $this->bookRepository->isExistBy(['id' => $id])) {
            return ['success' => false, 'error' => 'book doesn\'t exist'];
        }

        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $book->setTitle($title)
             ->setAuthors($this->updateBookAuthors($authors))
             ->setDescription($description)
             ->setYear($year)
             ->setImage($data['image'] ?? '');

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return ['success' => true];
    }

    public function delete(int $id): array
    {
        if ( ! $this->bookRepository->isExistBy(['id' => $id])) {
            return ['success' => false, 'error' => 'book doesn\'t exist'];
        }

        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $this->entityManager->remove($this->entityManager->merge($book));
        $this->entityManager->flush();

        return ['success' => true];
    }

    public function getBook(int $id): array
    {
        if ( ! $this->bookRepository->isExistBy(['id' => $id])) {
            return ['success' => false, 'error' => 'book doesn\'t exist'];
        }

        $book    = $this->bookRepository->findOneBy(['id' => $id]);
        $authors = array();
        foreach ($book->getAuthors() as $author) {
            $authors[] = [
                'id'   => $author->getId(),
                'name' => $author->getName()
            ];
        }

        return (new BookItemResponse(
            $book->getId(),
            $book->getTitle(),
            $authors,
            $book->getDescription(),
            $book->getYear(),
            $book->getImage()))->serialize();
    }

    public function getListBook(
        ?int $fromAuthors,
        ?int $toAuthors,
        ?int $fromYear,
        ?int $toYear
    ): array {
        $books      = $this->bookRepository->findAll();
        $booksArray = array();
        foreach ($books as $book) {
            if ($book->getAuthors()->count() >= $fromAuthors
                and $book->getAuthors()->count() <= ($toAuthors ??
                                                     $book->getAuthors()
                                                          ->count())
                    and $book->getYear() >= $fromYear
                        and $book->getYear() <= ($toYear ?? $book->getYear())
            ) {
                $authorsArray = array();
                foreach ($book->getAuthors() as $author) {
                    $authorsArray[] = [
                        'id'   => $author->getID(),
                        'name' => $author->getName()
                    ];
                }
                $booksArray[] = (new BookItemResponse(
                    $book->getId(),
                    $book->getTitle(),
                    $authorsArray,
                    $book->getDescription(),
                    $book->getYear(),
                    $book->getImage()))->serialize();
            }
        }

        return (new BookListResponse($booksArray))->serialize();
    }

    public function getListBookAuthorsFilterDqb(): array
    {
        $books      = $this->bookRepository->findMoreTwoAuthorsDqb();
        $booksArray = array();
        foreach ($books as $book) {
            $book         = $book[0];
            $authorsArray = array();
            foreach ($book->getAuthors() as $author) {
                $authorsArray[] = [
                    'id'   => $author->getID(),
                    'name' => $author->getName()
                ];
            }
            $booksArray[] = (new BookItemResponse(
                $book->getId(),
                $book->getTitle(),
                $authorsArray,
                $book->getDescription(),
                $book->getYear(),
                $book->getImage()))->serialize();
        }

        return (new BookListResponse($booksArray))->serialize();
    }

    public function getListBookAuthorsFilterSql(): array
    {
        $books      = $this->bookRepository->findMoreTwoAuthorsSql();
        $booksArray = array();
        foreach ($books as $book) {
            $authorsArray = array();
            $authorsIds   = explode(',', $book['author_ids']);
            $authorsNames = explode(',', $book['author_names']);
            for ($i = 0; $i < $book['author_count']; $i++) {
                $authorsArray[] = [
                    'id'   => $authorsIds[$i],
                    'name' => $authorsNames[$i]
                ];
            }
            $booksArray[] = (new BookItemResponse(
                $book['id'],
                $book['title'],
                $authorsArray,
                $book['description'],
                $book['year'],
                $book['image']))->serialize();
        }

        return (new BookListResponse($booksArray))->serialize();
    }
}