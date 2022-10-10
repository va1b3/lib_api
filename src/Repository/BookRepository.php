<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findAll()
    {
        return parent::findAll();
    }

    public function isExistBy(array $criteria): bool
    {
        return null !== parent::findOneBy($criteria);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function findMoreTwoAuthorsDqb()
    {
        return $this->createQueryBuilder('q')
                    ->addSelect('SIZE(q.authors) as authors')
                    ->having('authors > 2')
                    ->getQuery()
                    ->getResult();
    }
    
    /**
     * @return mixed[][]
     * @throws Exception
     */
    public function findMoreTwoAuthorsSql()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = "SELECT
                    *,
                    GROUP_CONCAT(a.name) AS author_names,
                    GROUP_CONCAT(a.id) AS author_ids,
                    COUNT(a.id) AS author_count
                FROM lib_api.book b
                LEFT JOIN lib_api.books_authors ab
                    ON b.id = ab.book_id
                LEFT JOIN lib_api.author a
                    ON ab.author_id = a.id
                GROUP BY b.id
                HAVING author_count > 2";

        return $conn->prepare($sql)->executeQuery()->fetchAllAssociative();
    }
}