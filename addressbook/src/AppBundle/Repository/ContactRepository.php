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
}
