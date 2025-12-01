<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sale;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserTeamHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sale>
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /**
     * @return list<Sale>
     */
    public function search(\DateTimeInterface $date, ?Team $team = null): array
    {
        $rsm = (new ResultSetMapping())
            ->addEntityResult(Sale::class, 's')
            ->addFieldResult('s', 's_id', 'id')
            ->addFieldResult('s', 's_date', 'date')
            ->addFieldResult('s', 's_amount', 'amount')
            ->addJoinedEntityResult(User::class, 'u', 's', 'user')
            ->addFieldResult('u', 'u_id', 'id')
            ->addFieldResult('u', 'u_username', 'username')
            ->addJoinedEntityResult(UserTeamHistory::class, 'uth', 'u', 'teamHistories')
            ->addFieldResult('uth', 'uth_id', 'id')
            ->addFieldResult('uth', 'uth_from_date', 'fromDate')
            ->addFieldResult('uth', 'uth_to_date', 'toDate')
            ->addJoinedEntityResult(Team::class, 't', 'uth', 'team')
            ->addFieldResult('t', 't_id', 'id')
            ->addFieldResult('t', 't_name', 'name')
        ;

        $query = $this->getEntityManager()->createNativeQuery(strval(file_get_contents(__DIR__.'/../../sql/sale.search.sql')), $rsm)
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('team_id', $team?->getId())
        ;

        /** @var list<Sale> $sales */
        $sales = $query->getResult();

        return $sales;
    }

    // /**
    //  * @return Sale[] Returns an array of Sale objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('s.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function findOneBySomeField($value): ?Sale
    // {
    //     return $this->createQueryBuilder('s')
    //         ->andWhere('s.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
}
