<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PaymentRepository
 * @package App\Repository
 */
class TariffRepository extends EntityRepository
{
    public function getTariffsQB()
    {
        return $this->createQueryBuilder('tariff')
            ->setMaxResults(2)
            ;
    }
}
