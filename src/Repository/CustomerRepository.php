<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Exception\CustomerException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function add(Customer $entity, bool $isUpdate = false): Customer
    {
        if (!$isUpdate) {
            $this->_em->persist($entity);
        }
        $this->_em->flush();

        return $entity;
    }

    public function remove(Customer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws CustomerException
     */
    public function getCustomer($customer_id): Customer
    {
        $customer = $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $customer_id)
            ->getQuery()
            ->getResult();

        if (!$customer) {
            throw CustomerException::notCustomerExists();
        }

        return $customer[0];
    }
}
