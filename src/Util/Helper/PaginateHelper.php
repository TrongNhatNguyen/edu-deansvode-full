<?php

namespace App\Util\Helper;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginateHelper
{
    protected $defaultPage = 1;
    protected $defaultItemPerPage = 25;

    private $requestStack;
    private $paginator;

    public function __construct(RequestStack $requestStack, PaginatorInterface $paginator)
    {
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
    }
 
    public function paginateHelper($queryBuilder, $page = null, $itemPerPage = null)
    {
        if (!$page) {
            $page = $this->defaultPage;
        }
        if (!$itemPerPage) {
            $itemPerPage = $this->defaultItemPerPage;
        }
        return $this->paginator->paginate(
            $queryBuilder,
            $this->requestStack->getCurrentRequest()->query->getInt('page', $page),
            $itemPerPage
        );
    }
}
