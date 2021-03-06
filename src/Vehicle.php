<?php

namespace Vroom;

use Geocoder\Model\Coordinates;

/**
 * a Vehicle represents a method of transportation with certain capacities for accomplishing tasks.
 * vehicles will have an address that they start from and end at. for instance, if a vehicle must start from homebase 1
 * and needs to go to point A, point B, point C, and point D in some arbitrary order, and then it must return to
 * homebase 2, start would be homebase 1 and end would be homebase 2.
 */
class Vehicle
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $profile = 'car';

    /**
     * @var Coordinates
     */
    private $start;

    /**
     * @var Coordinates
     */
    private $end;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProfile(string $profile)
    {
        $this->profile = $profile;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setStart(Coordinates $start)
    {
        $this->start = $start;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setEnd(Coordinates $end)
    {
        $this->end = $end;
    }

    public function getEnd()
    {
        return $this->end;
    }
}
