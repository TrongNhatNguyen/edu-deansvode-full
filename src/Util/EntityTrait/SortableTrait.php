<?php

namespace App\Util\EntityTrait;

use Doctrine\ORM\Mapping as ORM;

trait SortableTrait
{
    /**
     * @ORM\Column(type="smallint")
     */
    private $sort;

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort)
    {
        $this->sort = $sort;
    }
}
