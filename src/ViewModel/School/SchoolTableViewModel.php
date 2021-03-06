<?php

namespace App\ViewModel\School;

use Twig\Environment;
use App\Service\ZoneService;
use App\Service\CountryService;
use Symfony\Component\HttpFoundation\Request;

class SchoolTableViewModel
{
    public const HEADER_FIELDS = [
        [],
    ];

    private $itemsPerPage = 50;

    private $countryService;

    private $zoneService;

    private $twig;

    private $queryObject;

    public function __construct(
        CountryService $countryService,
        ZoneService $zoneService,
        Environment $twig
    ) {
        $this->countryService = $countryService;
        $this->zoneService = $zoneService;
        $this->twig = $twig;
    }

    public function renderMainSchoolTableBody()
    {
        $countries = $this->countryService->getAllCountriesByAlphabeticalOrder();
        $zones = $this->zoneService->getAllZonesByAlphabeticalOrder();

        $html = $this->twig->render('');

        return $html;
    }
}
