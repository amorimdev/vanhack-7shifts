<?php

namespace AppBundle\Component;

use AppBundle\Contract\SevenShiftsDataProviderInterface;

require __DIR__ . '/../../../vendor/mcaskill/php-array-group-by/Function.Array-Group-By.php';

/**
 * Class SevenShiftsDataProvider
 * @package AppBundle\Component
 */
class SevenShiftsDataProvider implements SevenShiftsDataProviderInterface
{
    /**
     * @var array
     */
    private $locations;

    /**
     * @var array
     */
    private $users;

    /**
     * @var array
     */
    private $timePunches;

    /**
     * @param array $locations
     * @return $this
     */
    public function setLocations(array $locations): SevenShiftsDataProviderInterface
    {
        $this->locations = $locations;

        return $this;
    }

    /**
     * @param array $users
     * @return $this
     */
    public function setUsers(array $users): SevenShiftsDataProviderInterface
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param array $timePunches
     * @return $this
     */
    public function setTimePunches(array $timePunches): SevenShiftsDataProviderInterface
    {
        $this->timePunches = $timePunches;

        return $this;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $dataNormalize = [];
        $timePunchesByLocationAndUser = array_group_by($this->timePunches, 'locationId', 'userId');

        foreach ($timePunchesByLocationAndUser as $locationKey => $users) {
            $dataNormalize[] = array_merge($this->locations[$locationKey], ['users'=> []]);
            $key = array_search($locationKey, array_column($dataNormalize, 'id'));

            foreach ($users as $userKey => $timePunches) {
                $dataNormalize[$key]['users'][] = array_merge(
                    $this->users[$locationKey][$userKey],
                    ['timePunches' => $timePunches]
                );
            }
        }

        return $dataNormalize;
    }
}
