<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;
use App\Entity\User;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
    * @return Post[] Returns an array of Post objects
    */
    public function findPost($title, $category, $username)
    {

        if ($title && $category && $username)
        {
            $qb = $this->createQueryBuilder('p')
                ->andWhere('p.title = :title')
                ->andWhere('p.category = :category')
                ->andWhere('p.user = :username')
                ->setParameter('title', $title)
                ->setParameter('category', $category['category'])
                ->setParameter('username', $username);

            $query = $qb->getQuery();
        }
        else if ($title && $category)
        {
            $qb = $this->createQueryBuilder('p')
                ->andWhere('p.title = :title')
                ->andWhere('p.category = :category')
                ->setParameter('title', $title)
                ->setParameter('category', $category['category']);

            $query = $qb->getQuery();
        }
        else if ($category && $username)
        {
            $qb = $this->createQueryBuilder('p')
                ->andWhere('p.user = :username')
                ->andWhere('p.category = :category')
                ->setParameter('username', $username)
                ->setParameter('category', $category['category']);

            $query = $qb->getQuery();
        }
        else if ($category)
        {
            $qb = $this->createQueryBuilder('p')
                ->andWhere('p.category = :category')
                ->setParameter('category', $category['category']);

            $query = $qb->getQuery();
        }
        return $query->execute();
    }
}
