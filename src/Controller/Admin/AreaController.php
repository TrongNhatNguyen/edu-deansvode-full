<?php

namespace App\Controller\Admin;

use App\DTO\Request\Area\CreateRequest;
use App\DTO\Request\Area\CurrentRequest;
use App\DTO\Request\Area\RemoveRequest;
use App\DTO\Request\Area\UpdateRequest;
use App\DTO\Request\Area\UpdateStatusRequest;

use App\Service\Admin\ZoneService;
use App\Util\Helper\PaginateHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AreaController extends AbstractController
{
    private $zoneService;
    private $paginateHelper;

    public function __construct(
        ZoneService $zoneService,
        PaginateHelper $paginateHelper
    ) {
        $this->zoneService = $zoneService;
        $this->paginateHelper = $paginateHelper;
    }

    /**
     * @Route("/area", name="area", methods={"GET"})
     */
    public function index(Request $request)
    {
        $reqParams = $request->query->all();

        if (empty($reqParams)) {
            $allAreasQuery = $this->zoneService->getAllZonesQuery();
            $pagination = $this->paginateHelper->paginateHelper($allAreasQuery);

            return $this->render('admin/page/area/index.html.twig', [
                'pagination' => $pagination,
                'listAreas' => $pagination->getItems(),
                'itemsPerPage' => $this->paginateHelper->defaultItemPerPage
            ]);
        }

        $listQuery = $this->zoneService->buildZoneListQuery($reqParams);
        $areaQueryBuilder = $this->zoneService->getZoneQueryBuilder($listQuery);

        $this->paginateHelper->setPage($listQuery->page);
        $this->paginateHelper->setItemsPerPage($listQuery->limit);
        $pagination = $this->paginateHelper->paginateHelper($areaQueryBuilder);

        return $this->json([
            'status' => 'success',
            'html' => $this->renderView('admin/page/area/partial/list_area.html.twig', [
                'listAreas' => $pagination->getItems()
            ]),
            'htmlPaging' => $this->renderView('admin/page/area/partial/paging_area.html.twig', [
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
     * @Route("/#show-area-info", name="show_area_info", methods={"GET"})
     */
    public function showCurrentInfo(CurrentRequest $currentRequest)
    {
        // validation:
        if (isset($currentRequest->errors)) {
            return $this->json(['status' => 'failed', 'messages' => $currentRequest->errors]);
        }

        $areaUpdate = $this->zoneService->getZoneById($currentRequest->id);

        $html = $this->renderView('admin/page/area/partial/form_update_area.html.twig', [
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

        $result = $this->zoneService->updateStatus($updateStatusRequest);

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
