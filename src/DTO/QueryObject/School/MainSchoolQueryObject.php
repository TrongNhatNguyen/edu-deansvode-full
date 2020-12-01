<?php

namespace App\DTO\QueryObject\School;

use App\DTO\QueryObject\BaseQueryObject;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class MainSchoolQueryObject extends BaseQueryObject
{
    public const ALIAS = 'school';

    private $entityManager;

    private $queryBuilder;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->queryBuilder = $entityManager->createQueryBuilder(self::ALIAS);
    }


}
