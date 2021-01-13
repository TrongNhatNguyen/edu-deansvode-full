<?php

namespace App\Service\Dean;

use App\Repository\DeanRepository;

class DeanQueryBuilder
{
    private $deanRepository;

    public function __construct(DeanRepository $deanRepository)
    {
        $this->deanRepository = $deanRepository;
    }


    public function getAllDeansActive()
    {
        return $this->deanRepository->createQueryBuilder('d')
            ->select('d.firstName', 'd.lastName', 'd.title', 'd.email1', 'd.email2')
            ->andWhere('d.status = :status_val')
            ->setParameter('status_val', 1)
            ->getQuery()->getResult();
    }
}
