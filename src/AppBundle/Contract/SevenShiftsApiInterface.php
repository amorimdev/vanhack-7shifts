<?php

namespace AppBundle\Contract;

/**
 * Interface SevenShiftsApiInterface
 * @package AppBundle\Contract
 */
interface SevenShiftsApiInterface
{
    /**
     * @return array
     */
    public function getLocations(): array;

    /**
     * @return array
     */
    public function getUsers(): array;

    /**
     * @return array
     */
    public function getTimePunches(): array;
}
