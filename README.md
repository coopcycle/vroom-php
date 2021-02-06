vroom-php
=========

Installation
------------

The recommended way to install **vroom-php** is through [composer](http://getcomposer.org).

Run the following on the command line:

```
php composer require coopcycle/vroom-php
```

Usage
-----

```php

use Geocoder\Model\Coordinates;
use Vroom\Client;
use Vroom\Job;
use Vroom\RoutingProblem;
use Vroom\Vehicle;

$vehicle = new Vehicle(1);
$vehicle->setStart(new Coordinates(48.87261892829001, 2.3363113403320312));

$problem = new RoutingProblem();
$problem->addVehicle($vehicle);

$coordinates = [
    new Coordinates(48.87261892829001, 2.3363113403320312),
    new Coordinates(48.86923158125418, 2.3548507690429683),
    new Coordinates(48.87600604599898, 2.3466110229492188),
];

foreach ($coordinates as $id => $coordinate) {
    $job = new Job($id);
    $job->setLocation($coordinate);

    $problem->addJob($job);
}

$client = new Client(['base_uri' => 'http://localhost:3000']);

$response = $client->solve($problem);
```
