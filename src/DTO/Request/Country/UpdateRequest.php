<?php

namespace App\DTO\Request\Country;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Validator\Admin\Constraints\Country as CustomAssert;
use App\Validator\Admin\Constraints\Area as ZoneCustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\Resource
     */
    public $id;

    /**
     * @ZoneCustomAssert\Resource
     */
    public $zoneId;

    /**
     * @CustomAssert\Identities
     */
    protected $identities;

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

    /**
     * @Assert\NotBlank(
     *      message="Date updated is required!"
     * )
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $updatedAt;


    public function __construct(Request $request)
    {
        $this->buildByRequest($request);
    }

    public function buildByRequest(Request $request)
    {
        $this->id = (int) $request->get('country_id', null);
        $this->zoneId = (int) $request->get('zone_id', '');
        $this->name = $request->get('country_name', '');
        $this->slug = $request->get('country_slug', '');
        $this->isoCode = $request->get('country_iso_code', '');
        $this->sort = (int) $request->get('country_sort', 0);
        $this->status = (int) $request->get('country_status', 0);
        $this->updatedAt = new \DateTime($request->get('updated_at', 'now'));

        $this->identities = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'isoCode' => $this->isoCode
        ];
    }
}
