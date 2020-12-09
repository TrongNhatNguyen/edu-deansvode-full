<?php

namespace App\Controller\Admin;

use App\Service\Admin\ZoneService;
use App\Service\Admin\CountryService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class CountryController extends AbstractController
{
    private $countryService;
    private $zoneService;

    public function __construct(CountryService $countryService, ZoneService $zoneService)
    {
        $this->countryService = $countryService;
        $this->zoneService = $zoneService;
    }


    /**
     * @Route("/country", name="admin_country")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {

        $queryParams = $request->query->all();

        if (empty($queryParams)) {
            $zones = $this->zoneService->getAllZones();

            $countriesQuery = $this->countryService->getAllCountriesQuery();
            $pagination = $paginator->paginate(
                $countriesQuery, /* query NOT result */
                $request->query->getInt('page', 1),
                25
            );

            return $this->render('admin/page/country/index.html.twig', [
                'zones' => $zones,
                'countries' => $pagination,
                'itemsPerPage' => 25 // custom default
            ]);
        }

        $countryListQuery = $this->countryService->buildCountryListQuery($queryParams);
        $newList = $this->countryService->getListCountry($countryListQuery);
        $pagination = $paginator->paginate(
            $newList['queryBuilder'], /* query NOT result */
            $request->query->getInt('page', $newList['page']),
            $newList['limit']
        );
        $html = $this->renderView('admin/page/country/partial/list_country.html.twig', [
            'countries' => $pagination
        ]);

        return $this->json([
            'status' => "success",
            'html' => $html
        ]);
    }

    /**
     * @Route("/#create-country-action", name="admin_create_country_action")
     */
    public function createCountryAction(Request $request)
    {
        $data = $request->request->all();

        $result = $this->countryService->createCountryAction($data);

        return $this->json($result);
    }

    /**
     * @route("/#show-update-country", name="admin_show_country_update", methods="GET")
     */
    public function showUpdateCountry(Request $request)
    {
        $country_id = $request->query->get('country_id');
        $countryUpdate = $this->countryService->getCountryById($country_id);
        
        $zones = $this->zoneService->getAllZones();

        $html = $this->renderView('admin/page/country/partial/form_update_country.html.twig', [

            'zones' => $zones,

            'id' => $countryUpdate->getId(),
            'zone_id' => $countryUpdate->getZone()->getId(),
            'name' => $countryUpdate->getName(),
            'slug' => $countryUpdate->getSlug(),
            'iso_code' => $countryUpdate->getIsoCode(),
            'status' => $countryUpdate->getStatus()
        ]);

        return $this->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    /**
     * @route("/#update-country-action", name="admin_update_country_action")
     */
    public function updateCountryAction(Request $request)
    {
        $data = $request->request->all();
        $result = $this->countryService->updateCountryAction($data);

        return $this->json($result);
    }

    /**
     * @route("/#update-status-country-action", name="admin_update_status_country_action")
     */
    public function updateStatusCountryAction(Request $request)
    {
        $data = $request->request->all();
        $result = $this->countryService->updateStatusAction($data);

        return $this->json($result);
    }

    /**
     * @route("/#delete-country-action", name="admin_delete_country_action")
     */
    public function deleteCountryAction(Request $request)
    {
        $country_id = $request->query->get('country_id');
        $result = $this->countryService->deleteCountryAction($country_id);

        return $this->json($result);
    }
}
