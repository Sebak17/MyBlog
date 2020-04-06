<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getLastArticles($size = 2)
    {
        return $this->createQueryBuilder('a')
                    ->where('a.status = :status')
                    ->setParameter('status', 'VISIBLE')
                    ->orderBy('a.created_at', 'DESC')
                    ->setMaxResults($size)
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function findByTitle($title)
    {
        $title = strtolower($title);

        return $this->createQueryBuilder('a')
                    ->where('LOWER(a.title) LIKE :title')
                    ->setParameter('title', '%' . $title . '%')
                    ->orderBy('a.created_at', 'ASC')
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function findByTag($tag, $like = true, $onlyVisible = false)
    {
        $tag = strtolower($tag);

        if ($like) {
            $tag = '%' . $tag . '%';
        }

        $query = $this->createQueryBuilder('a')
                      ->where('LOWER(a.tag) LIKE :tag')
                      ->setParameter('tag', $tag)
                      ->orderBy('a.created_at', 'ASC');

        if ($onlyVisible) {
            $query->andWhere('a.status LIKE :status')
                  ->setParameter('status', 'VISIBLE');
        }

        return $query->getQuery()->getResult();
    }

    public function findByMonth()
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        return $this->createQueryBuilder('a')
                    ->where('YEAR(a.created_at) = YEAR(CURRENT_DATE()) AND MONTH(a.created_at) = MONTH(CURRENT_DATE())')
                    ->orderBy('a.created_at', 'ASC')
                    ->getQuery()
                    ->getResult()
        ;
    }

}
