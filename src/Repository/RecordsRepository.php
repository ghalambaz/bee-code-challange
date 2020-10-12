<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Record;
use App\Form\Model\RecordSearchModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Record|null find($id, $lockMode = null, $lockVersion = null)
 * @method Record|null findOneBy(array $criteria, array $orderBy = null)
 * @method Record[]    findAll()
 * @method Record[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    public function searchByParamsOrderByArtist_Title(RecordSearchModel $searchModel)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('r', 'a')
            ->from(Record::class, 'r')
            ->leftJoin('r.artist', 'a');

        if ($searchModel->getTitle()) {
            $query->andWhere('r.title LIKE :title')->setParameter('title', '%' . $searchModel->getTitle() . '%');
        }
        if ($searchModel->getDescription()) {
            $query->andWhere('r.description LIKE :description')->setParameter(
                'description',
                '%' . $searchModel->getDescription() . '%'
            );
        }
        if ($searchModel->getArtist()) {
            $query->andWhere('a.name LIKE :artist')->setParameter('artist', '%' . $searchModel->getArtist() . '%');
        }
        return $query->orderBy('a.name,r.title')->getQuery()->getResult();
    }
    // /**
    //  * @return Record[] Returns an array of Record objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Record
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
