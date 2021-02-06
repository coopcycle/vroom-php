<?php

namespace Vroom;

/**
 * @see https://github.com/VROOM-Project/vroom/blob/master/docs/API.md#shipments
 */
class Shipment
{
    /**
     * a shipment_step object describing pickup
     * @var Job
     */
    public $pickup;

    /**
     * a shipment_step object describing delivery
     * @var Job
     */
    public $delivery;

    public function __construct(Job $pickup, Job $delivery)
    {
        $this->pickup = $pickup;
        $this->delivery = $delivery;
    }
}
