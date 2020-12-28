<?php

namespace App\Controller\Admin;

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
     * @Route("/area", name="area")
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
    public function create(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->createZone($data);

        return $this->json($result);
    }

    /**
     * @Route("/#show-area-info", name="show_area_info")
     */
    public function showCurrentInfo(Request $request)
    {
        $id = $request->query->get('area_id');
        
        $areaUpdate = $this->zoneService->getZoneById($id);

        $html = $this->renderView('admin/page/area/partial/form_update_area.html.twig', [
            'areaUpdate' => $areaUpdate
        ]);

        return $this->json(['status' => 'success', 'html' => $html]);
    }

    /**
     * @Route("/#update-area", name="update_area_action")
     */
    public function update(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->updateZone($data);

        return $this->json($result);
    }

    /**
     * @Route("/#update-status-area", name="update_status_area_action")
     */
    public function updateStatus(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->updateStatus($data);

        return $this->json($result);
    }

    /**
     * @Route("/#delete-area", name="delete_area_action")
     */
    public function delete(Request $request)
    {
        $id = $request->query->get('area_id');
        $result = $this->zoneService->deleteZone($id);

        return $this->json($result);
    }
}
