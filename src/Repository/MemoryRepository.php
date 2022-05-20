<?php

namespace App\Repository;

use App\Entity\Memory;
use App\Exception\MemoryException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Memory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Memory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Memory[]    findAll()
 * @method Memory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Memory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Memory $entity, bool $flush = true): Memory
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
    public function remove(Memory $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws MemoryException
     */
    public function getMemory($id): Memory
    {
        $memory = $this->createQueryBuilder('m')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        if (!$memory) {
            throw MemoryException::notMemoryExists();
        }

        return $memory[0];
    }
}
