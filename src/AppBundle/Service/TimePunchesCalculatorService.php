<?php

namespace AppBundle\Service;

use AppBundle\Contract\CalculatorInterface;
use AppBundle\Contract\DataProviderInterface;

/**
 * Class TimePunchesCalculatorService
 * @package AppBundle\Service
 */
class TimePunchesCalculatorService implements CalculatorInterface, DataProviderInterface
{
    /**
     * @var array
     */
    private $dataProvider;

    /**
     * @param array $dataProvider
     */
    public function setDataProvider(array $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return array
     */
    public function calculate(): array
    {
        /** @var array $weeksOfMonthWorkedTime Data storage of hours worked and overtime per week */
        $daysOfMonthWorkedTime = [];
        /** @var array $weeksOfMonthWorkedTime Data storage of hours worked and overtime per week */
        $weeksOfMonthWorkedTime = [];

        foreach ($this->dataProvider as $locationKey => $location) {
            foreach ($location['users'] as $userKey => $user) {
                foreach ($user['timePunches'] as $timePunchKey => $timePunch) {
                    $clockedIn = new \DateTime($timePunch['clockedIn']);
                    $clockedOut = new \DateTime($timePunch['clockedOut']);
                    $timeDiff = $clockedOut->diff($clockedIn);
                    $monthKey = (int) $clockedIn->format('m');

                    /** @var int $dailyWorkedTime Calculation of hours worked in the day (in minutes) */
                    $dayKey = (int) $clockedIn->format('d');
                    $dailyWorkedTime = $timeDiff->i + ($timeDiff->h * 60);

                    if (!isset($daysOfMonthWorkedTime[$monthKey][$dayKey])) {
                        $daysOfMonthWorkedTime[$monthKey][$dayKey] = [
                            'workedTime' => 0,
                            'overtime' => 0,
                        ];
                    }

                    $daysOfMonthWorkedTime[$monthKey][$dayKey]['workedTime'] += $dailyWorkedTime;

                    if ($daysOfMonthWorkedTime[$monthKey][$dayKey]['workedTime'] >
                        $location['labourSettings']['dailyOvertimeThreshold']) {
                        $daysOfMonthWorkedTime[$monthKey][$dayKey]['overtime'] +=
                            $daysOfMonthWorkedTime[$monthKey][$dayKey]['workedTime'] -
                            $location['labourSettings']['dailyOvertimeThreshold'];
                        $daysOfMonthWorkedTime[$monthKey][$dayKey]['workedTime'] =
                            $location['labourSettings']['dailyOvertimeThreshold'];
                    }
                    /** End calculation day hours */

                    /** @var int $dailyWorkedTime Calculation of hours worked in the week (in minutes) */

                    if (!isset($weeksOfMonthWorkedTime[$monthKey])) {
                        $weeksOfMonthWorkedTime[$monthKey] = [];
                    }

                    $weekKey = $this->weekOfMonth($clockedIn);

                    if (!isset($weeksOfMonthWorkedTime[$monthKey][$weekKey])) {
                        $weeksOfMonthWorkedTime[$monthKey][$weekKey] = [
                            'workedTime' => 0,
                            'overtime' => 0,
                        ];
                    }

                    $weeksOfMonthWorkedTime[$monthKey][$weekKey]['workedTime'] += $dailyWorkedTime;

                    if ($weeksOfMonthWorkedTime[$monthKey][$weekKey]['workedTime'] >
                        $location['labourSettings']['weeklyOvertimeThreshold']) {
                        $weeksOfMonthWorkedTime[$monthKey][$weekKey]['overtime'] +=
                            $weeksOfMonthWorkedTime[$monthKey][$weekKey]['workedTime'] -
                            $location['labourSettings']['weeklyOvertimeThreshold'];
                        $weeksOfMonthWorkedTime[$monthKey][$weekKey]['workedTime'] =
                            $location['labourSettings']['weeklyOvertimeThreshold'];
                    }
                    /** End calculation week hours */
                }

                /** Total Worked Time Values (in minutes) */
                $this->dataProvider[$locationKey]['users'][$userKey]['dailyWorkedTime'] =
                    array_sum(array_map(function($month) {
                        $workedTime = array_sum(array_map(function($day) {
                            return $day['workedTime'];
                        }, $month));

                        return $workedTime;
                    }, $daysOfMonthWorkedTime)) / 60;

                $this->dataProvider[$locationKey]['users'][$userKey]['dailyOvertime'] =
                    array_sum(array_map(function($month) {
                        $overtime = array_sum(array_map(function($day) {
                            return $day['overtime'];
                        }, $month));

                        return $overtime;
                    }, $daysOfMonthWorkedTime)) / 60;

                $this->dataProvider[$locationKey]['users'][$userKey]['weeklyWorkedTime'] =
                    array_sum(array_map(function($month) {
                        $workedTime = array_sum(array_map(function($week) {
                            return $week['workedTime'];
                        }, $month));

                        return $workedTime;
                    }, $weeksOfMonthWorkedTime)) / 60;

                $this->dataProvider[$locationKey]['users'][$userKey]['weeklyOvertime'] =
                    array_sum(array_map(function($month) {
                        $overtime = array_sum(array_map(function($week) {
                            return $week['overtime'];
                        }, $month));

                        return $overtime;
                    }, $weeksOfMonthWorkedTime)) / 60;
            }
        }

        return $this->dataProvider;
    }

    /**
     * @param \DateTime $dateTime
     * @return int
     */
    private function weekOfMonth(\DateTime $dateTime)
    {
        $firstDayOfMonth = new \DateTime($dateTime->format('Y/m/1'));
        $firstDay = $firstDayOfMonth->format('N');
        $dayOfMonth = $dateTime->format('j');

        return (int) floor(((int) $firstDay + (int) $dayOfMonth - 1) / 7);
    }
}
