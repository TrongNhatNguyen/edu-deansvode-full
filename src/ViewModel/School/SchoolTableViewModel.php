<?php

namespace App\ViewModel\School;

use Twig\Environment;
use App\Service\ZoneService;
use App\Service\CountryService;
use App\Service\VoteSessionService;
use Symfony\Component\HttpFoundation\Request;

class SchoolTableViewModel
{
    public const HEADER_FIELDS = [
        [],
    ];

    private $itemsPerPage = 50;

    private $schoolPartialDir = 'admin/page/school/partial';

    private $numberYearsInNavbar = 4;

    private $countryService;

    private $zoneService;

    private $voteSessionService;

    private $twig;

    private $queryObject;

    public function __construct(
        CountryService $countryService,
        ZoneService $zoneService,
        VoteSessionService $voteSessionService,
        Environment $twig
    ) {
        $this->countryService = $countryService;
        $this->zoneService = $zoneService;
        $this->voteSessionService = $voteSessionService;
        $this->twig = $twig;
    }

    public function renderNavbarYears(int $number = null)
    {
        $limit = !empty($number) ? $number : $this->numberYearsInNavbar;

        $voteSessions = $this->voteSessionService->getLatestSessionYears($limit);

        return $this->twig->render($this->schoolPartialDir . '/navbar_years.html.twig', [
            'voteSessions' => $voteSessions,
        ]);
    }

    public function renderMainSchoolTableHeader()
    {
        $countries = $this->countryService->getAllCountries();
        $zones = $this->zoneService->getAllZones();

        return $this->twig->render($this->schoolPartialDir . '/table_header.html.twig', [
            'countries' => $countries,
            'zones' => $zones,
        ]);
    }

    public function renderMainSchoolTableBody()
    {
        return $this->twig->render($this->schoolPartialDir . '/table_body.html.twig');
    }
}
