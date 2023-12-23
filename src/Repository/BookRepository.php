<?php

namespace App\Repository;

use App\Dto\ReadBookDto;
use App\Entity\Book;
use App\Wrapper\ApiPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

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

    public function add(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $filter
     * @param int $offset
     * @param int $limit
     * @return ApiPage
     */
    public function findByFilter(array $filter, int $offset = 0, int $limit = 20): ApiPage
    {
        $query = $this->createQueryBuilder("b")
            ->setMaxResults($limit)
            ->setFirstResult($offset);


        if (array_key_exists('title', $filter)) {
            $query->add('where', $query->expr()->like('b.title', ':title'))
                ->setParameter('title', '%'.$filter['title'].'%');
        }

        if (array_key_exists('author', $filter)) {
            $query->add('where', $query->expr()->like('b.author', ':author'))
                ->setParameter('author', '%'.$filter['author'].'%');
        }

        if (array_key_exists('description', $filter)) {
            $query->add('where', $query->expr()->like('b.description', ':description'))
                ->setParameter('description', '%'.$filter['description'].'%');
        }

        if (array_key_exists('price', $filter)) {
            $query->where('b.price = :price')
                ->setParameter('price', $filter['price']);
        } elseif (array_key_exists('price_gt', $filter)) {
            $query->where('b.price > :price')
                ->setParameter('price', $filter['price_gt']);
        } elseif (array_key_exists('price_lt', $filter)) {
            $query->where('b.price < :price')
                ->setParameter('price', $filter['price_lt']);
        }

        $query->getQuery();

        $paginator =  new Paginator($query);
        $total = count($paginator);
        $content = new ArrayCollection();

        /** @var Book $book */
        foreach ($paginator as $book) {
            $content->add(ReadBookDto::to($book));
        }
        $content->count();


        return ApiPage::of($content, $total, $offset, $limit);
    }
}
