<?php

namespace App\Service\EmailTemplate;

use App\DTO\QueryObject\VoteSession\VoteSessionListQuery;
use App\Repository\EmailTemplateRepository;

class EmailTemplateQueryBuilder
{
    private $emailTemplateRepository;

    public function __construct(EmailTemplateRepository $emailTemplateRepository)
    {
        $this->emailTemplateRepository = $emailTemplateRepository;
    }


    public function buildEmailTemplateListQuery(array $request)
    {
        $voteSessionListQuery = new VoteSessionListQuery();

        foreach ($request as $key => $value) {
            if ($key === "status") {
                $voteSessionListQuery->conditions[$key] = $value;
            }
        }

        return $voteSessionListQuery;
    }

    public function getEmailTemplateByListQuery($listQuery)
    {
        $queryBuilder = $this->emailTemplateRepository->createQueryBuilder('e')
        ->select()
        ->addOrderBy('e.id', 'DESC');

        if (!empty($listQuery->conditions)) {
            foreach ($listQuery->conditions as $key => $value) {
                if ($value == null) {
                    $key = null;
                }

                switch ($key) {
                    case 'status':
                        $queryBuilder->andWhere('e.status = :status_val')
                        ->setParameter('status_val', $value);
                        break;
                    
                    default:
                        break;
                }
            }
        }

        return $queryBuilder;
    }
}
