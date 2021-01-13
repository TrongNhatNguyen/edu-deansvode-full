<?php

namespace App\DTO\Request\Area;

use App\DTO\Request\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Validator\Admin\Constraints\Area as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateRequest implements RequestDTOInterface
{
    /**
     * @CustomAssert\Name
     */
    public $name;

    /**
     * @CustomAssert\Slug
     */
    public $slug;

    /**
     * @Assert\NotBlank(
     *      message="Image is required!"
     * )
     */
    public $image;

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
     *      message="Date created is required!"
     * )
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    public $createdAt;

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
        $this->name = $request->get('area_name', '');
        $this->slug = $request->get('area_slug', '');
        $this->image = $request->get('area_image', 'no-image.png');
        $this->sort = (int) $request->get('area_sort', 0);
        $this->status = (int) $request->get('area_status', 0);
        $this->createdAt = new \DateTime($request->get('area_created_at', 'now'));
        $this->updatedAt = new \DateTime($request->get('area_updated_at', 'now'));
    }
}
