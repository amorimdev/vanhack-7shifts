<?php

namespace AppBundle\Contract;

/**
 * Interface SevenShiftsDataInterface
 * @package AppBundle\Contract
 */
interface SevenShiftsDataInterface
{
    /**
     * @return array
     */
    public function getNormalizedData(): array;
}
