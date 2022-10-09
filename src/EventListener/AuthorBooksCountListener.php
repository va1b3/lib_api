<?php

namespace App\EventListener;

use App\Event\PreAddEvent;
use App\Event\PreDeleteEvent;
use App\Event\PreUpdateEvent;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class AuthorBooksCountListener
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly AuthorRepository $authorRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function preAdd(PreAddEvent $preAddEvent): void
    {
        foreach ($preAddEvent->getData()['authors'] as $authorName) {
            $author = $this->authorRepository->findOneBy(['name' => $authorName]);
            if ($author !== null) {
                $author->setBooksNumber($author->getBooksNumber() + 1);
                $this->entityManager->persist($author);
            }
        }
    }

    public function preUpdate(PreUpdateEvent $preUpdateEvent): void
    {
        $book = $this->bookRepository->findOneBy(
            ['title' => $preUpdateEvent->getData()['title']]);

        $authorsExisted = array();
        foreach ($book->getAuthors() as $author) {
            $authorsExisted[] = $author->getName();
        }
        $authorsAdded = array_diff(
            $preUpdateEvent->getData()['authors'],
            $authorsExisted);
        $authorsRemoved = array_diff(
            $authorsExisted,
            $preUpdateEvent->getData()['authors']);

        foreach ($authorsAdded as $authorName) {
            $author = $this->authorRepository->findOneBy(['name' => $authorName]);
            if ($author !== null) {
                $author->setBooksNumber($author->getBooksNumber() + 1);
                $this->entityManager->persist($author);
            }
        }

        foreach ($authorsRemoved as $authorName) {
            $author = $this->authorRepository->findOneBy(['name' => $authorName]);
            $author->setBooksNumber($author->getBooksNumber() - 1);
            $this->entityManager->persist($author);
        }
    }

    public function preDelete(PreDeleteEvent $preDeleteEvent): void
    {
        $book = $this->bookRepository->findOneBy(['id' => $preDeleteEvent->getId()]);
        foreach ($book->getAuthors() as $author) {
            $author->setBooksNumber($author->getBooksNumber() - 1);
            $this->entityManager->persist($author);
        }
    }
}