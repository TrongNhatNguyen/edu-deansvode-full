<?php

namespace App\Controller\Web;

use App\Service\VoteManagerService;
use App\Service\Zone\ZoneFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private $ZoneFetcher;
    private $voteSessionService;

    public function __construct(ZoneFetcher $ZoneFetcher, VoteManagerService $voteSessionService)
    {
        $this->ZoneFetcher = $ZoneFetcher;
        $this->voteSessionService = $voteSessionService;
    }

    /**
     * @Route("zone", name="list_zone")
     */
    public function index()
    {
        $allZones = $this->ZoneFetcher->getAllZonesActiveByAlphaOrder();
        return $allZones;
    }
}
