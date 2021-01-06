<?php

namespace App\DTO\Request\Country;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Validator\Admin\Constraints\Area as ZoneCustomAssert;
use App\Validator\Admin\Constraints\Country as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRequest implements RequestDTOInterface
{
    /**
     * @ZoneCustomAssert\Resource
     */
    public $zoneId;

    /**
     * @CustomAssert\Name
     */
    public $name;

    /**
     * @CustomAssert\Slug
     */
    public $slug;

    /**
     * @CustomAssert\IsoCode
     */
    public $isoCode;

    /**
     * @Assert\NotBlank(
     *      message="Sort is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $sort;

    /**
     * @Assert\NotBlank(
     *      message="Active is required!"
     * )
     * @Assert\type(type="integer")
     */
    public $status;


    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->zoneId = (int) $request->get('zone_id', '');
        $this->name = $request->get('country_name', '');
        $this->slug = $request->get('country_slug', '');
        $this->isoCode = $request->get('country_iso_code', '');
        $this->sort = (int) $request->get('country_sort', 0);
        $this->status = (int) $request->get('country_status', 0);
    }
}
