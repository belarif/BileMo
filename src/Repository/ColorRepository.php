<?php

namespace App\Repository;

use App\Entity\Color;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Color|null find($id, $lockMode = null, $lockVersion = null)
 * @method Color|null findOneBy(array $criteria, array $orderBy = null)
 * @method Color[]    findAll()
 * @method Color[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Color::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Color $entity, bool $flush = true): Color
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Color $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $id
     * @return Color
     * @throws EntityNotFoundException
     */
    public function getColor($id):Color
    {
        $color = $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if(!$color) {
            throw new EntityNotFoundException('La couleur demandé n\'existe pas');
        }

        return $color[0];
    }
}

