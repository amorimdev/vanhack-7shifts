<?php

namespace AppBundle\Service;

use AppBundle\Contract\CalculatorInterface;
use AppBundle\Contract\SevenShiftsDataInterface;

/**
 * Class SevenShiftsTimePunchesCalculatorService
 * @package AppBundle\Service
 */
class SevenShiftsTimePunchesCalculatorService implements CalculatorInterface
{
    /**
     * @var SevenShiftsDataService
     */
    private $dataService;

    /**
     * @var TimePunchesCalculatorService
     */
    private $calculatorService;

    /**
     * SevenShiftsDataService constructor.
     * @param SevenShiftsDataInterface $dataService
     * @param TimePunchesCalculatorService $calculatorService
     */
    public function __construct(SevenShiftsDataInterface $dataService, TimePunchesCalculatorService $calculatorService)
    {
        $this->dataService = $dataService;
        $this->calculatorService = $calculatorService;
    }

    /**
     * @return array
     */
    public function calculate(): array
    {
        $this->calculatorService->setDataProvider($this->dataService->getNormalizedData());

        return $this->calculatorService->calculate();
    }
}
