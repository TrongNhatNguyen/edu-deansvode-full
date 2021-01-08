<?php

namespace App\Controller\Admin;

use App\DTO\Request\Country\CreateRequest;
use App\DTO\Request\Country\CurrentRequest;
use App\DTO\Request\Country\RemoveRequest;
use App\DTO\Request\Country\UpdateRequest;
use App\DTO\Request\Country\UpdateStatusRequest;

use App\Service\Admin\ZoneService;
use App\Service\Admin\CountryService;
use App\Util\Helper\PaginateHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/admin", name="admin_")
 */
class CountryController extends AbstractController
{
    public $countryDir = 'admin/page/country/';
    public $countryPartialDir = 'admin/page/country/partial/';

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
     * @Route("/country", name="country", methods={"GET"})
     */
    public function index(Request $request)
    {
        $reqParams = $request->query->all();

        if (empty($reqParams)) {
            $zones = $this->zoneService->getAllZones();
            $countriesQuery = $this->countryService->getAllCountriesQuery();

            $pagination = $this->paginateHelper->paginateHelper($countriesQuery);

            return $this->render($this->countryDir . 'index.html.twig', [
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
            'html' => $this->renderView($this->countryPartialDir . 'list_country.html.twig', [
                'countries' => $pagination->getItems()
            ]),
            'htmlPaging' => $this->renderView($this->countryPartialDir . 'paging_country.html.twig', [
                'pagination' => $pagination,
                'numberOfPage' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
            ])
        ]);
    }

    /**
     * @Route("/#create-country", name="create_country_action")
     */
    public function create(CreateRequest $createRequest)
    {
        // validation:
        if (isset($createRequest->errors)) {
            return $this->json(['status' =>'failed', 'messages' => $createRequest->errors]);
        }

        $result = $this->countryService->createCountry($createRequest);

        return $this->json($result);
    }

    /**
     * @route("/#show-country-info", name="show_country_info", methods={"GET"})
     */
    public function showCurrentInfo(CurrentRequest $currentRequest)
    {
        // validation:
        if (isset($currentRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $currentRequest->errors]);
        }
        
        $countryUpdate = $this->countryService->getCountryById($currentRequest->id);
        $zones = $this->zoneService->getAllZones();

        $html = $this->renderView($this->countryPartialDir . 'form_update_country.html.twig', [
            'zones' => $zones,
            'countryUpdate' => $countryUpdate
        ]);

        return $this->json(['status' => 'success', 'html' => $html]);
    }

    /**
     * @route("/#update-country", name="update_country_action")
     */
    public function update(UpdateRequest $updateRequest)
    {
        // validation:
        if (isset($updateRequest->errors)) {
            return $this->json(['status' =>'failed', 'messages' => $updateRequest->errors]);
        }
        
        $result = $this->countryService->updateCountry($updateRequest);

        return $this->json($result);
    }

    /**
     * @route("/#update-status-country", name="update_status_country_action")
     */
    public function updateStatus(UpdateStatusRequest $updateStatusRequest)
    {
        // validation:
        if (isset($updateStatusRequest->errors)) {
            return $this->json(['status' =>'failed', 'messages' => $updateStatusRequest->errors]);
        }

        $result = $this->countryService->updateStatusCountry($updateStatusRequest);

        return $this->json($result);
    }

    /**
     * @route("/#delete-country", name="delete_country_action", methods={"GET"})
     */
    public function remove(RemoveRequest $removeRequest)
    {
        // validation:
        if (isset($removeRequest->errors)) {
            return $this->json(['status' =>'failed', 'messages' => $removeRequest->errors]);
        }

        $result = $this->countryService->deleteCountry($removeRequest->id);

        return $this->json($result);
    }
}
