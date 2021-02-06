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
            new Coordinates(43.738648, 7.427826),
            new Coordinates(43.737888, 7.423567),
            new Coordinates(43.736183, 7.418642)
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
        $vehicle1->setStart(new Coordinates(43.734849, 7.420563));

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
