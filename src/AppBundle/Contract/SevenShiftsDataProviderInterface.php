<?php

namespace AppBundle\Contract;

/**
 * Interface SevenShiftsDataProviderInterface
 * @package AppBundle\Contract
 */
interface SevenShiftsDataProviderInterface
{
    /**
     * @param array $locations
     * @return SevenShiftsDataProviderInterface
     */
    public function setLocations(array $locations): SevenShiftsDataProviderInterface;

    /**
     * @param array $users
     * @return SevenShiftsDataProviderInterface
     */
    public function setUsers(array $users): SevenShiftsDataProviderInterface;

    /**
     * @param array $timePunches
     * @return SevenShiftsDataProviderInterface
     */
    public function setTimePunches(array $timePunches): SevenShiftsDataProviderInterface;

    /**
     * @return array
     */
    public function build(): array;
}
