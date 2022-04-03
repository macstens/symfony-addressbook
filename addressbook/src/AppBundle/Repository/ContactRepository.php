<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContactRepository extends EntityRepository
{
    public function findAllOrderedByLastName(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.lastname', 'ASC')
            ->getQuery()->getResult();
    }

    public function findBySearchQuery(string $query): array 
    {
        return $this->createQueryBuilder('a')
        ->where('a.lastname LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$query.'%')
        ->orWhere('a.firstname LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$query.'%')
        ->getQuery()->getResult();
    }
}
