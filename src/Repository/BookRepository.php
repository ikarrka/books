<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\Author;
use App\Repository\AuthorRepository;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Book $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);

        $this->updateAuthorsCollection($entity);

        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Book $entity, bool $flush = true): void
    {
        $this->updateAuthorsCollection($entity);

        foreach ($entity->getAuthors() as $author) {
            $entity->addAuthor($author);
        }

        if ($flush) {
            $this->_em->flush();

        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Book $entity, bool $flush = true): void
    {
        try {
            $this->updateAuthorsCollection($entity);
            
            $this->_em->remove($entity);
            if ($flush) {
                $this->_em->flush();
            }
        } catch (Exception $ex) {
            throw new Exception("Something went wrong");
        }
    }

    private function updateAuthorsCollection(Book $entity)
    {
        foreach ($entity->getAuthors() as $author) {
            $entity->addAuthor($author);
        }

    }


// /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('b')
      ->andWhere('b.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('b.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Book
      {
      return $this->createQueryBuilder('b')
      ->andWhere('b.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function filterByFields($value)
    {
        return $this->createQueryBuilder('a')
            ->orWhere('a.title like :val')
            ->orWhere('a.description like :val')
            ->orWhere('a.year like :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult();
    }

}
