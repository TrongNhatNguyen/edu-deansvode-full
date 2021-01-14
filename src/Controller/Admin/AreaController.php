<?php

namespace App\Controller\Admin;

use App\DTO\Request\Area\CreateRequest;
use App\DTO\Request\Area\CurrentRequest;
use App\DTO\Request\Area\RemoveRequest;
use App\DTO\Request\Area\UpdateRequest;
use App\DTO\Request\Area\UpdateStatusRequest;

use App\Service\Zone\ZoneFetcher;
use App\Service\ZoneService;

use App\Util\Helper\PaginateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AreaController extends AbstractController
{
    public $areaDir = 'admin/page/area/';
    public $areaPatialDir = 'admin/page/area/partial/';

    private $zoneService;
    private $zoneFetcher;
    private $paginateHelper;

    public function __construct(
        ZoneService $zoneService,
        ZoneFetcher $zoneFetcher,
        PaginateHelper $paginateHelper
    ) {
        $this->zoneService = $zoneService;
        $this->zoneFetcher = $zoneFetcher;
        $this->paginateHelper = $paginateHelper;
    }

    /**
     * @Route("/area", name="area", methods={"GET"})
     */
    public function index(Request $request)
    {
        $request = $request->query->all();

        if (empty($request)) {
            $allAreasQuery = $this->zoneService->listAllZoneQuery();
            $pagination = $this->paginateHelper->paginateHelper($allAreasQuery);

            return $this->render($this->areaDir . 'index.html.twig', [
                'pagination' => $pagination,
                'listAreas' => $pagination->getItems(),
                'itemsPerPage' => $this->paginateHelper->defaultItemPerPage
            ]);
        }

        $listAreaQuery = $this->zoneService->listZoneQuery($request);

        $this->paginateHelper->setPage($request['page']);
        $this->paginateHelper->setItemsPerPage($request['limit']);
        $pagination = $this->paginateHelper->paginateHelper($listAreaQuery);

        return $this->json([
            'status' => 'success',
            'html' => $this->renderView($this->areaPatialDir . 'list_area.html.twig', [
                'listAreas' => $pagination->getItems()
            ]),
            'htmlPaging' => $this->renderView($this->areaPatialDir . 'paging_area.html.twig', [
                'pagination' => $pagination
            ])
        ]);
    }

    /**
     * @Route("/#create-area", name="create_area_action")
     */
    public function create(CreateRequest $createRequest)
    {
        // validation:
        if (isset($createRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $createRequest->errors]);
        }

        $result = $this->zoneService->createZone($createRequest);

        return $this->json($result);
    }

    /**
     * @Route("/#show-area-info-update", name="show_area_info", methods={"GET"})
     */
    public function showCurrentInfoUpdate(CurrentRequest $currentRequest)
    {
        // validation:
        if (isset($currentRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $currentRequest->errors]);
        }

        $areaUpdate = $this->zoneFetcher->getZoneById($currentRequest->id);

        $html = $this->renderView($this->areaPatialDir . 'form_update_area.html.twig', [
            'areaUpdate' => $areaUpdate
        ]);

        return $this->json(['status' => 'success', 'html' => $html]);
    }

    /**
     * @Route("/#update-area", name="update_area_action")
     */
    public function update(UpdateRequest $updateRequest)
    {
        // validation:
        if (isset($updateRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $updateRequest->errors]);
        }

        $result = $this->zoneService->updateZone($updateRequest);

        return $this->json($result);
    }

    /**
     * @Route("/#update-status-area", name="update_status_area_action")
     */
    public function updateStatus(UpdateStatusRequest $updateStatusRequest)
    {
        //validation:
        if (isset($updateStatusRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $updateStatusRequest->errors]);
        }

        $result = $this->zoneService->updateZone($updateStatusRequest);

        return $this->json($result);
    }

    /**
     * @Route("/#delete-area", name="delete_area_action", methods={"GET"})
     */
    public function remove(RemoveRequest $removeRequest)
    {
        // validation:
        if (isset($removeRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $removeRequest->errors]);
        }

        $result = $this->zoneService->removeZone($removeRequest->id);

        return $this->json($result);
    }
}
