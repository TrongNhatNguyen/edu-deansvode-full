<?php

namespace App\Util;

use Doctrine\ORM\EntityManagerInterface;

class TransactionUtil
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function begin()
    {
        $this->entityManager->getConnection()->beginTransaction();
    }

    public function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    public function commit()
    {
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();
    }

    public function rollBack()
    {
        $this->entityManager->getConnection()->rollBack();
    }

    public function remove($entity)
    {
        $this->entityManager->remove($entity);
    }
}
