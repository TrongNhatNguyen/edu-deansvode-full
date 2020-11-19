<?php

namespace App\Controller\Web;

use App\Service\Web\ZoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ZoneController extends AbstractController
{
    private $ZoneService;

    public function __construct(ZoneService $zoneService)
    {
        $this->ZoneService = $zoneService;
    }

    /**
     * @Route("home", name="listCategories")
     */
    public function index()
    {
        $allZones = $this->ZoneService->getAllZonesByAlphabeticalOrder();
        dd($allZones);
        return $allZones;
    }
}
