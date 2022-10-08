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

class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private AuthorRepository $authorRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function add(array $data) : array
    {
        try {
            $title = $data['title'];
            $authors = $data['authors'];
            $description = $data['description'];
            $year = $data['year'];
        } catch (\Exception $exception) {
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

        $authorsCollection = new ArrayCollection();

        foreach ($authors as $author) {
            if (!$this->authorRepository->isExistBy(['name' => $author])) {
                $newAuthor = (new Author())
                    ->setName($author)
                    ->setBooksNumber(1);

                $authorsCollection->add($newAuthor);
                $this->entityManager->persist($newAuthor);
            }
        }

        $book->setAuthors($authorsCollection);
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return ['success' => true];
    }

    public function update(array $data) : array
    {
        try {
            $id = $data['id'];
            $title = $data['title'];
            $authors = $data['authors'];
            $description = $data['description'];
            $year = $data['year'];
        } catch (\Exception $exception) {
            return ['success' => false, 'error' => $exception->getMessage()];
        }

        if (!$this->bookRepository->isExistBy(['id' => $id])) {
            return ['success' => false, 'error' => 'book doesn\'t exist'];
        }

        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $authorsCollection = new ArrayCollection();
        foreach ($authors as $author) {
            if (!$this->authorRepository->isExistBy(['name' => $author])) {
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

        $book->setTitle($title)
             ->setAuthors($authorsCollection)
             ->setDescription($description)
             ->setYear($year)
             ->setImage($data['image'] ?? '');

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return ["success" => true];
    }

    public function delete(int $id) : array
    {
        if (!$this->bookRepository->isExistBy(['id' => $id])) {
            return ['success' => false, 'error' => 'book doesn\'t exist'];
        }

        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $this->entityManager->remove($this->entityManager->merge($book));
        $this->entityManager->flush();

        return ["success" => true];
    }

    public function getBook(): BookItemResponse
    {
        // output
    }

    public function getListBook($authors = null, $year = null): BookListResponse
    {
        // output
    }

    public function getListBookAuthorsFilterSql(): BookItemResponse
    {
        // output
    }

    public function getListBookAuthorsFilterDqb(): BookItemResponse
    {
        // output
    }
}
