<?php

namespace AppBundle\Service;

use AppBundle\Component\SevenShiftsDataProvider;
use AppBundle\Contract\SevenShiftsApiInterface;
use AppBundle\Contract\SevenShiftsDataInterface;

/**
 * Class SevenShiftsDataService
 * @package AppBundle\Service
 */
class SevenShiftsDataService implements SevenShiftsDataInterface
{
    /**
     * @var SevenShiftsApiService
     */
    private $apiService;

    /**
     * SevenShiftsDataService constructor.
     * @param SevenShiftsApiInterface $apiService
     */
    public function __construct(SevenShiftsApiInterface $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @return array
     */
    public function getNormalizedData(): array
    {
        $dataProvider = new SevenShiftsDataProvider();

        return $dataProvider->setLocations($this->apiService->getLocations())
            ->setUsers($this->apiService->getUsers())
            ->setTimePunches($this->apiService->getTimePunches())
            ->build();
    }
}
