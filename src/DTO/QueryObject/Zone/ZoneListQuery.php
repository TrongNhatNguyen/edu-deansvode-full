<?php

namespace App\DTO\QueryObject\Zone;

class ZoneListQuery
{
    public $selectedFields = [];

    public $conditions = [];

    public $orders = [];

    public $page;

    public $limit;

    public $offset;
}
