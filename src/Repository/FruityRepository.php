<?php

/*
 * FruityRepository (This file is Repository file and responsible for make database logic and fire queries based on service request)
 * Author : Amar Shah
 * Company : QuanticEdge Software Solutions
 */

namespace App\Repository;

use App\Entity\Fruity;
use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 *
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruity::class);
    }
    /**
     * @param string|null $term
     */
    public function getData()
    {
        $qb = $this->createQueryBuilder("f");
        $qb->orderBy("f.id", "DESC");
        return $qb;
    }

    /**
     * (This method include code of fetch main page data with name and family filters)
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function getSearchQuery($filters, $fav_page)
    {
        if ($fav_page) {
            $qb = $this->createQueryBuilder("f")->andWhere("f.is_fav = :is_fav")->setParameter("is_fav", 1);
        } else {
            $qb = $this->createQueryBuilder("f");
        }
        if (!is_null($filters['name_filter']) && $filters['name_filter'] !== "") {
            $qb->andWhere("f.name LIKE :name")->setParameter("name", "%" . trim($filters['name_filter']) . "%");
        }
        if (!is_null($filters['family_filter']) && $filters['family_filter'] !== "") {
            $qb->andWhere("f.family LIKE :family")->setParameter("family", "%" . trim($filters['family_filter']) . "%");
        }
        $qb->orderBy("f.id", "DESC");
        return $qb;
    }

    /**
     * (This method include code of delete fruit)
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function delete($id): void
    {
        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->delete(Fruity::class, 'f')
            ->where('f.id = :Id')
            ->setParameter('Id', $id)
            ->getQuery()
            ->execute();
    }

    /**
     * (This method include code of add to favorite list fruit)
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function addToFav($id)
    {
        $queryBuilder = $this->createQueryBuilder('f')->andWhere('f.is_fav = :is_fav')->setParameter('is_fav', 1)->getQuery()->getArrayResult();
        $c = count($queryBuilder);
        if ($c >= 10) {
            return "reach_limit";
        }

        $queryBuilder =  $this->createQueryBuilder('f');
        $queryBuilder->update(Fruity::class, 'f')
            ->set('f.is_fav', ':is_fav')
            ->where('f.id = :editId')
            ->setParameter('is_fav', 1)
            ->setParameter('editId', $id)
            ->getQuery()
            ->execute();
    }

    /**
     * (This method include code of remove from favorite list fruit)
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function removeFromFav($id): void
    {
        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->update(Fruity::class, 'f')
            ->set('f.is_fav', ':is_fav')
            ->where('f.id = :editId')
            ->setParameter('is_fav', 0)
            ->setParameter('editId', $id)
            ->getQuery()
            ->execute();
    }

    /**
     * (This method include code of get global sum of all exists fruits nutritions facts)
     * Author : Amar Shah
     * Company : QuanticEdge Software Solutions
     */
    public function getGlobalSum($type)
    {
        $queryBuilder = $this->createQueryBuilder('f');
        if ($type == "favorite") {
            $queryBuilder = $queryBuilder->andWhere('f.is_fav = :is_fav')->setParameter('is_fav', 1);
        }
        $queryBuilder = $queryBuilder->getQuery()->getArrayResult();
        $nutritionsArr = array_column($queryBuilder, "nutritions");
        $globalSum = 0;
        foreach ($nutritionsArr as $value) {
            $globalSum = $globalSum +  array_sum($value);
        }
        return $globalSum;
    }
}
