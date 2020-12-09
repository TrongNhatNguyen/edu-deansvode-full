<?php

namespace App\Controller\Web;

use App\Service\Web\VoteSessionService;
use App\Service\Web\ZoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private $ZoneService;
    private $voteSessionService;

    public function __construct(ZoneService $zoneService, VoteSessionService $voteSessionService)
    {
        $this->ZoneService = $zoneService;
        $this->voteSessionService = $voteSessionService;
    }

    /**
     * @Route("zone", name="listCategories")
     */
    public function index()
    {
        $allZones = $this->ZoneService->getAllZonesActiveByAlphaOrder();
        dd($allZones);
        return true;
    }
}
