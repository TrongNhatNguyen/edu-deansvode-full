<?php

namespace App\Controller\Admin;

use App\Service\Admin\ZoneService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AreaController extends AbstractController
{
    private $zoneService;

    public function __construct(ZoneService $zoneService)
    {
        $this->zoneService = $zoneService;
    }

    /**
     * @Route("/area", name="admin_area")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        if (empty($request->query->all())) {
            $allAreasQuery = $this->zoneService->getAllZonesQuery();
            $pagination = $paginator->paginate(
                $allAreasQuery,
                $request->query->getInt('page', 1),
                25
            );
            return $this->render('admin/page/area/index.html.twig', [
                'listAreas' => $pagination,
                'itemsPerPage' => 25 // custom default
            ]);
        }

        $zoneListQuery = $this->zoneService->buildZoneListQuery($request->query->all());
        $newList = $this->zoneService->getListZoneQuery($zoneListQuery);
        $pagination = $paginator->paginate(
            $newList['queryBuilder'],
            $request->query->getInt('page', $newList['page']),
            $newList['limit']
        );

        $html = $this->renderView('admin/page/area/partial/list_area.html.twig', [
            'listAreas' => $pagination
        ]);

        return $this->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    /**
     * @route("/#create-area-action", name="admin_create_area_action")
     */
    public function createAreaAction(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->createZoneAction($data);

        return $this->json($result);
    }

    /**
     * @route("/#show-area-update", name="admin_show_area_update", methods="GET")
     */
    public function showAreaUpdate(Request $request)
    {
        $id = $request->query->get('area_id');
        
        $areaUpdate = $this->zoneService->getZoneById($id);

        $html = $this->renderView('admin/page/area/partial/form_update_area.html.twig', [
            'id' => $areaUpdate->getId(),
            'name' => $areaUpdate->getName(),
            'slug' => $areaUpdate->getSlug(),
            'status' => $areaUpdate->getStatus()
        ]);

        return $this->json([
            'status' => 'success',
            'html' => $html
        ]);
    }

    /**
     * @route("/#update-area-action", name="admin_update_area_action")
     */
    public function updateAreaAction(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->updateZoneAction($data);

        return $this->json($result);
    }

    /**
     * @route("/#update-status-area-action", name="admin_update_status_area_action")
     */
    public function updateStatusAreaAction(Request $request)
    {
        $data = $request->request->all();
        $result = $this->zoneService->updateStatusAction($data);

        return $this->json($result);
    }

    /**
     * @route("/#delete-area-action", name="admin_delete_area_action", methods="GET")
     */
    public function deleteAreaAction(Request $request)
    {
        $id = $request->query->get('area_id');
        $result = $this->zoneService->deleteZoneAction($id);

        return $this->json($result);
    }
}
