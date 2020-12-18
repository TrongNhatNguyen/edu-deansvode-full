<?php

namespace App\DTO\QueryObject\VoteSession;

class VoteSessionListQuery
{
    public $selectedFields = [];

    public $conditions = [];

    public $orders = [];

    public $page;

    public $limit;

    public $offset;
}
