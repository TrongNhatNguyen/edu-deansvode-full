<?php

namespace App\DTO\QueryObject;

use Symfony\Component\HttpFoundation\Request;
use App\Util\Helper\RequestHelper;
use App\Util\Helper\StringHelper;

class BaseQueryObject
{
    protected $selectedFields = [];

    protected $ignoredFields = [];

    protected $orders = [];

    protected $limit = null;

    // protected $page = null;

    protected $offset = null;

    protected $criteria = [];

    public function mapFromRequest(Request $request)
    {
        $params = RequestHelper::retrieveParams($request);

        foreach ($params as $field => $value) {
            switch ($field) {
                case 'select':
                    $this->selectedFields = $value;
                    break;
                case 'ignored':
                    $this->ignoredFields = $value;
                    break;
                case 'order':
                    $field = StringHelper::underscoreToCamelCase($field);
                    $this->orders[$field] = $value;
                    break;
                case 'limit':
                    $this->limit = $value;
                    break;
                case 'page':
                    // $this->page = $value;
                    $this->offset = !empty($this->limit) ? $this->limit * max($value - 1, 0) : null;
                    break;
                default:
                    $field = StringHelper::underscoreToCamelCase($field);
                    $this->criteria[$field] = $value;
                    break;
            }
        }
    }
}
