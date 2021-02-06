<?php

namespace Vroom\Tests;

use Geocoder\Model\Coordinates;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Vroom\Client;
use Vroom\Job;
use Vroom\RoutingProblem;
use Vroom\Vehicle;

class ClientTest extends TestCase
{
    public function testSolve()
    {
        $coords = [
            new Coordinates(48.87261892829001, 2.3363113403320312),
            new Coordinates(48.86923158125418, 2.3548507690429683),
            new Coordinates(48.876006045998984,  2.3466110229492188)
        ];

        $jobs = [];
        $jobId = 0;

        $now = new \DateTime();

        $after = clone $now;
        $after->modify('+5 minutes');

        $before = clone $now;
        $before->modify('+10 minutes');

        foreach ($coords as $coord) {

            $job = new Job(++$jobId);
            $job->setLocation($coord);
            $job->timeWindows = [
                [
                    (int) $after->format('U'),
                    (int) $before->format('U')
                ]
            ];

            $jobs[] = $job;
        }

        $vehicle1 = new Vehicle(1);
        $vehicle1->setProfile('bike');
        $vehicle1->setStart($coords[0]);
        $vehicle1->setEnd($coords[0]);

        $routingProblem = new RoutingProblem();

        foreach ($jobs as $job){
            $routingProblem->addJob($job);
        }
        $routingProblem->addVehicle($vehicle1);

        $client = new Client(['base_uri' => 'http://localhost:3000']);

        $response = $client->solve($routingProblem);

        $this->assertInstanceOf(Response::class, $response);
    }
}
