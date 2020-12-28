<?php

namespace App\Util\Helper;

use Knp\Component\Pager\PaginatorInterface;

class PaginateHelper
{
    public $defaultPage = 1;
    public $defaultItemPerPage = 25;

    private $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    public function setItemsPerPage(int $itemsPerPage)
    {
        $this->defaultItemPerPage = $itemsPerPage;
    }

    public function setPage(int $page)
    {
        $this->defaultPage = $page;
    }
 
    public function paginateHelper($queryBuilder)
    {
        return $this->paginator->paginate(
            $queryBuilder,
            $this->defaultPage,
            $this->defaultItemPerPage
        );
    }
}
