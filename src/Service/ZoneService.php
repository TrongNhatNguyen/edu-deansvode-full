<?php

namespace App\Service;

use App\Service\Zone\ZoneCreator;
use App\Service\Zone\ZoneFetcher;
use App\Service\Zone\ZoneQueryBuilder;
use App\Service\Zone\ZoneUpdator;
use App\Util\TransactionUtil;
use Symfony\Component\Routing\RouterInterface;

class ZoneService
{
    private $zoneFetcher;
    private $zoneCreator;
    private $zoneUpdator;
    private $zoneQueryBuilder;
    private $transactionUtil;
    private $router;

    public function __construct(
        ZoneFetcher $zoneFetcher,
        ZoneCreator $zoneCreator,
        ZoneUpdator $zoneUpdator,
        ZoneQueryBuilder $zoneQueryBuilder,
        TransactionUtil $transactionUtil,
        RouterInterface $router
    ) {
        $this->zoneFetcher = $zoneFetcher;
        $this->zoneCreator = $zoneCreator;
        $this->zoneUpdator = $zoneUpdator;
        $this->zoneQueryBuilder = $zoneQueryBuilder;
        $this->transactionUtil = $transactionUtil;
        $this->router = $router;
    }

    /// ================================ CRUD:
    public function createZone($createRequest)
    {
        $this->transactionUtil->begin();
        try {
            $zone = $this->zoneCreator->fromRequest($createRequest);

            $this->transactionUtil->persist($zone);
            $this->transactionUtil->commit();
            
            return [
                'status' => 'success',
                'message' => 'create new area is successfully!',
                'url' => $this->router->generate('admin_area')
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateZone($updateRequest)
    {
        $this->transactionUtil->begin();
        try {
            $zone = $this->zoneUpdator->fromRequest($updateRequest);

            $this->transactionUtil->persist($zone);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'update area successfully!',
                'url' => $this->router->generate('admin_area')
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function removeZone($id)
    {
        $this->transactionUtil->begin();
        try {
            $zone = $this->zoneFetcher->getZoneById($id);

            $this->transactionUtil->remove($zone);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'successfully deleted this area!',
                'url' => $this->router->generate('admin_area')
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    // [search-sort-filter by query params]:
    public function listAllZoneQuery()
    {
        return $this->zoneQueryBuilder->getAllZonesQuery();
    }

    public function listZoneQuery($request)
    {
        $listQuery = $this->zoneQueryBuilder->buildZoneListQuery($request);

        $listZoneQuery = $this->zoneQueryBuilder->getZoneByListQuery($listQuery);

        return $listZoneQuery;
    }

    public function listZoneExport($request)
    {
        $listQuery = $this->zoneQueryBuilder->buildZoneListQuery($request);

        $queryBuilder = $this->zoneQueryBuilder->getZoneByListQuery($listQuery);

        return $queryBuilder->getQuery()->getResult();
    }
}
