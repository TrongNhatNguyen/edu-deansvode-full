<?php

namespace App\DTO\QueryObject\Country;

class CountryListQuery
{
    public $selectedFields = [];

    public $conditions = [];

    public $orders = [];

    public $limit;

    public $page;

    public $offset;
}
