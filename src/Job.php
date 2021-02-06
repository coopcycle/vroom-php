<?php

namespace Vroom;

use Geocoder\Model\Coordinates;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @see https://github.com/VROOM-Project/vroom/blob/master/docs/API.md#jobs
 */
class Job
{
    /**
     * an integer used as unique identifier
     * @var int
     */
    public $id;

    /**
     * coordinates array
     * @var Coordinates
     */
    public $location;

    /**
     * an array of time_window objects describing valid slots for job service start
     * @var array
     * @SerializedName("time_windows")
     */
    public $timeWindows;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Coordinates
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Coordinates $location
     *
     * @return self
     */
    public function setLocation(Coordinates $location)
    {
        $this->location = $location;

        return $this;
    }
}
