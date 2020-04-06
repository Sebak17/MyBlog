<?php

namespace App\Repository;

use App\Entity\ViewCounter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ViewCounter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViewCounter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViewCounter[]    findAll()
 * @method ViewCounter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewCounterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewCounter::class);
    }

    public function findByArticleAndIp($article_id, $ip)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        return $this->createQueryBuilder('v')
                    ->where('v.article = :article_id')
                    ->setParameter('article_id', $article_id)
                    ->andWhere('v.ip = :ip')
                    ->setParameter('ip', $ip)
                    ->andWhere('DAY(v.date) = DAY(CURRENT_DATE())')
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }

    public function findByMonth()
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');

        return $this->createQueryBuilder('v')
                    ->where('YEAR(v.date) = YEAR(CURRENT_DATE()) AND MONTH(v.date) = MONTH(CURRENT_DATE())')
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function getViewsPerMonth(
)    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT MONTH(view_counter.date) as month, COUNT(view_counter.id) as amount  FROM view_counter WHERE YEAR(view_counter.date) = YEAR(CURRENT_DATE()) GROUP BY YEAR(view_counter.date), MONTH(view_counter.date)';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = array();

        for($i = 1 ; $i <= 12 ; $i++) {
            $result[$i] = 0;
        }

        while ($row = $stmt->fetch()) {
            $result[$row['month']] = $row['amount'];
        }

        


        return $result;
    }
}
