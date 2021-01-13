<?php

namespace App\Service;

use App\Service\Country\CountryCreator;
use App\Service\Country\CountryFetcher;
use App\Service\Country\CountryQueryBuilder;
use App\Service\Country\CountryUpdator;
use App\Util\TransactionUtil;

use Symfony\Component\Routing\RouterInterface;

class CountryService
{
    private $countryFetcher;
    private $countryCreator;
    private $countryUpdator;
    private $countryQueryBuilder;
    private $transactionUtil;
    private $router;

    public function __construct(
        CountryFetcher $countryFetcher,
        CountryCreator $countryCreator,
        CountryUpdator $countryUpdator,
        CountryQueryBuilder $countryQueryBuilder,
        TransactionUtil $transactionUtil,
        RouterInterface $router
    ) {
        $this->countryFetcher = $countryFetcher;
        $this->countryCreator = $countryCreator;
        $this->countryUpdator = $countryUpdator;
        $this->countryQueryBuilder = $countryQueryBuilder;
        $this->transactionUtil = $transactionUtil;
        $this->router = $router;
    }

    // =============== CRUD:
    public function createCountry($createRequest)
    {
        $this->transactionUtil->begin();
        try {
            $country = $this->countryCreator->fromRequest($createRequest);

            $this->transactionUtil->persist($country);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'create new country successfully!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function updateCountry($updateRequest)
    {
        $this->transactionUtil->begin();
        try {
            $country = $this->countryUpdator->fromRequest($updateRequest);

            $this->transactionUtil->persist($country);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'update country successfully!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();
            
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    public function deleteCountry($id)
    {
        $this->transactionUtil->begin();
        try {
            $country = $this->countryFetcher->getCountryById($id);

            $this->transactionUtil->remove($country);
            $this->transactionUtil->commit();

            return [
                'status' => 'success',
                'message' => 'successfully deleted this country!',
                'url' => $this->router->generate('admin_country')
            ];
        } catch (\Exception $ex) {
            $this->transactionUtil->rollBack();

            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }

    // [search-sort-filter by params]:
    public function listAllCountriesQuery()
    {
        return $this->countryQueryBuilder->getAllCountriesQuery();
    }

    public function listCountryQuery($request)
    {
        $listQuery = $this->countryQueryBuilder->buildCountryListQuery($request);

        $listCountryQuery = $this->countryQueryBuilder->getCountryByListQuery($listQuery);

        return $listCountryQuery;
    }

    public function listCountryExport($request)
    {
        $listQuery = $this->countryQueryBuilder->buildCountryListQuery($request);

        $queryBuilder = $this->countryQueryBuilder->getCountryByListQuery($listQuery);

        return $queryBuilder->getQuery()->getResult();
    }
}
