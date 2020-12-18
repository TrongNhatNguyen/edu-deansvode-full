<?php

namespace App\Controller\Admin;

use App\Service\Admin\ZoneService;
use App\Util\Helper\PaginateHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AreaController extends AbstractController
{
    protected $defaultPage = 1;
    protected $defaultItemPerPage = 25;

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
     * @Route("/area", name="admin_area")
     */
    public function index(Request $request)
    {
        $reqParams = $request->query->all();

        if (empty($reqParams)) {
            $allAreasQuery = $this->zoneService->getAllZonesQuery();
            $pagination = $this->paginateHelper->paginateHelper($allAreasQuery);

            return $this->render('admin/page/area/index.html.twig', [
                'listAreas' => $pagination,
                'itemsPerPage' => $this->defaultItemPerPage
            ]);
        }

        $newList = $this->zoneService->getListZoneQuery($reqParams);
        $pagination = $this->paginateHelper->paginateHelper($newList['queryBuilder'], $newList['page'], $newList['limit']);

        return $this->json([
            'status' => 'success',
            'html' => $this->renderView('admin/page/area/partial/list_area.html.twig', ['listAreas' => $pagination])
        ]);
    }

    /**
     * @Route("/#create-area", name="admin_create_area_action")
     */
    public function create(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->createZone($data);

        return $this->json($result);
    }

    /**
     * @Route("/#show-area-info", name="admin_show_area_info")
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
     * @Route("/#update-area", name="admin_update_area_action")
     */
    public function update(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->updateZone($data);

        return $this->json($result);
    }

    /**
     * @Route("/#update-status-area", name="admin_update_status_area_action")
     */
    public function updateStatus(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->updateStatus($data);

        return $this->json($result);
    }

    /**
     * @Route("/#delete-area", name="admin_delete_area_action")
     */
    public function delete(Request $request)
    {
        $id = $request->query->get('area_id');
        $result = $this->zoneService->deleteZone($id);

        return $this->json($result);
    }
}
