<?php

namespace App\Controller\Admin;

use App\Service\Admin\ZoneService;
use App\Service\Admin\CountryService;
use App\Util\Helper\PaginateHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class CountryController extends AbstractController
{
    private $countryService;
    private $zoneService;
    private $paginateHelper;

    public function __construct(
        CountryService $countryService,
        ZoneService $zoneService,
        PaginateHelper $paginateHelper
    ) {
        $this->countryService = $countryService;
        $this->zoneService = $zoneService;
        $this->paginateHelper = $paginateHelper;
    }


    /**
     * @Route("/country", name="country")
     */
    public function index(Request $request)
    {
        $reqParams = $request->query->all();

        if (empty($reqParams)) {
            $zones = $this->zoneService->getAllZones();
            $countriesQuery = $this->countryService->getAllCountriesQuery();

            $pagination = $this->paginateHelper->paginateHelper($countriesQuery);

            return $this->render('admin/page/country/index.html.twig', [
                'zones' => $zones,
                'countries' => $pagination->getItems(),
                'pagination' => $pagination,
                'numberOfPage' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage()),
                'itemsPerPage' => $this->paginateHelper->defaultItemPerPage
            ]);
        }

        $listQuery = $this->countryService->buildCountryListQuery($reqParams);
        $countryQueryBuilder = $this->countryService->getCountryQueryBuilder($listQuery);

        $this->paginateHelper->setPage($listQuery->page);
        $this->paginateHelper->setItemsPerPage($listQuery->limit);
        $pagination = $this->paginateHelper->paginateHelper($countryQueryBuilder);

        return $this->json([
            'status' => "success",
            'html' => $this->renderView('admin/page/country/partial/list_country.html.twig', [
                'countries' => $pagination->getItems()
            ]),
            'htmlPaging' => $this->renderView('admin/page/country/partial/paging_country.html.twig', [
                'pagination' => $pagination,
                'numberOfPage' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
            ])
        ]);
    }

    /**
     * @Route("/#create-country", name="create_country_action")
     */
    public function create(Request $request)
    {
        $data = $request->request->all();

        $result = $this->countryService->createCountry($data);

        return $this->json($result);
    }

    /**
     * @route("/#show-country-info", name="show_country_info")
     */
    public function showCurrentInfo(Request $request)
    {
        $country_id = $request->query->get('country_id');
        $countryUpdate = $this->countryService->getCountryById($country_id);
        
        $zones = $this->zoneService->getAllZones();

        $html = $this->renderView('admin/page/country/partial/form_update_country.html.twig', [
            'zones' => $zones,
            'countryUpdate' => $countryUpdate
        ]);

        return $this->json(['status' => 'success', 'html' => $html]);
    }

    /**
     * @route("/#update-country", name="update_country_action")
     */
    public function update(Request $request)
    {
        $data = $request->request->all();
        $result = $this->countryService->updateCountry($data);

        return $this->json($result);
    }

    /**
     * @route("/#update-status-country", name="update_status_country_action")
     */
    public function updateStatus(Request $request)
    {
        $data = $request->request->all();
        $result = $this->countryService->updateStatusCountry($data);

        return $this->json($result);
    }

    /**
     * @route("/#delete-country", name="delete_country_action")
     */
    public function delete(Request $request)
    {
        $country_id = $request->query->get('country_id');
        $result = $this->countryService->deleteCountry($country_id);

        return $this->json($result);
    }
}
