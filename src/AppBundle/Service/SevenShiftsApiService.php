<?php

namespace AppBundle\Service;

use AppBundle\Contract\HttpInterface;
use AppBundle\Contract\SevenShiftsApiInterface;
use GuzzleHttp\Exception\ConnectException;

/**
 * Class SevenShiftsApiService
 * @package AppBundle\Service
 */
class SevenShiftsApiService implements SevenShiftsApiInterface
{
    /**
     * @var HttpService
     */
    private $httpService;

    /**
     * @var array
     */
    private $sevenShiftsApi;

    /**
     * SevenShiftsService constructor.
     * @param HttpInterface $httpService
     * @param array $sevenShiftsApi
     */
    public function __construct(HttpInterface $httpService, array $sevenShiftsApi)
    {
        $this->httpService = $httpService;
        $this->sevenShiftsApi = $sevenShiftsApi;
    }

    /**
     * @return array
     */
    public function getLocations(): array
    {
        $response = $this->httpService->get($this->sevenShiftsApi['locations_uri']);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        $response = $this->httpService->get($this->sevenShiftsApi['users_uri']);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return array
     */
    public function getTimePunches(): array
    {
        $response = $this->httpService->get($this->sevenShiftsApi['time_punches_uri']);

        return json_decode($response->getBody()->getContents(), true);
    }
}
