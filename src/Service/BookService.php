<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Event\PreAddEvent;
use App\Event\PreDeleteEvent;
use App\Event\PreUpdateEvent;
use App\Exception\BookAlreadyExistException;
use App\Exception\BookFieldsErrorException;
use App\Exception\BookNotFoundException;
use App\Model\AuthorItem;
use App\Model\BookItemResponse;
use App\Model\BookListResponse;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly AuthorRepository $authorRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher
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
            throw new BookFieldsErrorException();
        }

        if ($this->bookRepository->isExistBy(['title' => $title])) {
            throw new BookAlreadyExistException();
        }

        $preAddEvent = new PreAddEvent($data);
        $this->eventDispatcher->dispatch($preAddEvent, $preAddEvent::NAME);

        $book = (new Book())
            ->setTitle($title)
            ->setAuthors($this->updateBookAuthors($authors))
            ->setDescription($description)
            ->setYear($year)
            ->setImage(($data['image']) ?? '');

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
            throw new BookFieldsErrorException();
        }

        if ( ! $this->bookRepository->isExistBy(['id' => $id])) {
            throw new BookNotFoundException();
        }

        $preUpdateEvent = new PreUpdateEvent($data);
        $this->eventDispatcher->dispatch($preUpdateEvent, $preUpdateEvent::NAME);

        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $book->setTitle($title)
             ->setAuthors($this->updateBookAuthors($authors))
             ->setDescription($description)
             ->setYear($year)
             ->setImage($data['image'] ?? '');

        $this->entityManager->flush();

        return ['success' => true];
    }

    public function delete(int $id): array
    {
        if ( ! $this->bookRepository->isExistBy(['id' => $id])) {
            throw new BookNotFoundException();
        }

        $preDeleteEvent = new PreDeleteEvent($id);
        $this->eventDispatcher->dispatch($preDeleteEvent, $preDeleteEvent::NAME);

        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($this->entityManager->merge($book));
        $this->entityManager->flush();

        return ['success' => true];
    }

    public function getBook(int $id): array
    {
        if ( ! $this->bookRepository->isExistBy(['id' => $id])) {
            throw new BookNotFoundException();
        }

        $book    = $this->bookRepository->findOneBy(['id' => $id]);
        $authors = array();
        foreach ($book->getAuthors() as $author) {
            $authors[] = (new AuthorItem($author->getID(),
                $author->getName()))->serialize();
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
                and $book->getAuthors()->count() <= ($toAuthors ?? $book->getAuthors()->count())
                    and $book->getYear() >= $fromYear
                        and $book->getYear() <= ($toYear ?? $book->getYear())
            ) {
                $authors = array();
                foreach ($book->getAuthors() as $author) {
                    $authors[] = (new AuthorItem($author->getID(),
                        $author->getName()))->serialize();
                }
                $booksArray[] = (new BookItemResponse(
                    $book->getId(),
                    $book->getTitle(),
                    $authors,
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
            $book    = $book[0];
            $authors = array();
            foreach ($book->getAuthors() as $author) {
                $authors[] = (new AuthorItem($author->getID(),
                    $author->getName()))->serialize();
            }
            $booksArray[] = (new BookItemResponse(
                $book->getId(),
                $book->getTitle(),
                $authors,
                $book->getDescription(),
                $book->getYear(),
                $book->getImage()))->serialize();
        }

        return (new BookListResponse($booksArray))->serialize();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getListBookAuthorsFilterSql(): array
    {
        $books      = $this->bookRepository->findMoreTwoAuthorsSql();
        $booksArray = array();
        foreach ($books as $book) {
            $authors      = array();
            $authorsIds   = explode(',', $book['author_ids']);
            $authorsNames = explode(',', $book['author_names']);
            for ($i = 0; $i < $book['author_count']; $i++) {
                $authors[] = (new AuthorItem($authorsIds[$i],
                    $authorsNames[$i]))->serialize();
            }
            $booksArray[] = (new BookItemResponse(
                $book['id'],
                $book['title'],
                $authors,
                $book['description'],
                $book['year'],
                $book['image']))->serialize();
        }

        return (new BookListResponse($booksArray))->serialize();
    }
}