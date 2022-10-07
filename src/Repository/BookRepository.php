<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, Book::class);
    }

    public function findAll() : array
    {
        return parent::findAll();
    }

    public function findOne(int $id) : Book
    {
        return parent::findOneBy(['id' => $id]);
    }

    /**
     * @param Book $book
     * @return Book
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Book $book) : Book
    {
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        return $book;
    }

    /**
     * @param Book $book
     * @return Book
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Book $book) : Book
    {
        $this->entityManager->remove($this->entityManager->merge($book));
        $this->entityManager->flush();

        return $book;
    }

    /**
     * @param Book $book
     * @return Book
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Book $book) : Book
    {
        $this->entityManager->merge($book);
        $this->entityManager->flush();

        return $book;
    }
}