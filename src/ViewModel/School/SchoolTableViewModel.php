<?php

namespace App\ViewModel\School;

use Twig\Environment;
use App\Service\ZoneService;
use App\Service\CountryService;

class SchoolTableViewModel
{
    public const HEADER_FIELDS = [
        [],
    ];

    private $countryService;

    private $zoneService;

    private $twig;

    public function __construct(
        CountryService $countryService,
        ZoneService $zoneService,
        Environment $twig
    ) {
        $this->countryService = $countryService;
        $this->zoneService = $zoneService;
        $this->twig = $twig;
    }

    public function renderMainSchoolTableHeader()
    {
        $countries = $this->countryService->getAllCountriesByAlphabeticalOrder();
        $zones = $this->zoneService->getAllZonesByAlphabeticalOrder();

        $html = $this->twig->render('');

        return $html;
    }
}
