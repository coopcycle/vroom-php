<?php

namespace Vroom\Tests;

use Geocoder\Model\Coordinates;
use PHPUnit\Framework\TestCase;
use Vroom\Job;
use Vroom\RoutingProblem;
use Vroom\RoutingProblemNormalizer;
use Vroom\Vehicle;

class RoutingProblemNormalizerTest extends TestCase
{
    public function testNormalization()
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

            $job = new Job();
            $job->id = $jobId;
            $job->location = $coord->toArray();
            $job->time_windows = [
                [
                    (int) $after->format('U'),
                    (int) $before->format('U')
                ]
            ];

            $jobs[] = $job;

            $jobId += 1;
        }

        $vehicle1 = new Vehicle(1, 'bike', $coords[0], $coords[0]);
        $routingProblem = new RoutingProblem();

        foreach ($jobs as $job){
            $routingProblem->addJob($job);
        }
        $routingProblem->addVehicle($vehicle1);

        $normalizer = new RoutingProblemNormalizer();

        $result = $normalizer->normalize($routingProblem);

        $this->assertEquals([
            "jobs"=>[
                [
                    "id"=>0,
                    "location"=>[2.3363113403320312, 48.87261892829001],
                    "time_windows"=>[
                        [ (int) $after->format('U'), (int) $before->format('U') ]
                    ]
                ],
                [
                    "id"=>1,
                    "location"=>[2.3548507690429683, 48.86923158125418],
                    "time_windows"=>[
                        [ (int) $after->format('U'), (int) $before->format('U') ]
                    ]
                ],
                [
                    "id"=>2,
                    "location"=>[2.3466110229492188, 48.876006045998984],
                    "time_windows"=>[
                        [ (int) $after->format('U'), (int) $before->format('U') ]
                    ]
                ],
            ],
            "shipments"=>[],
            "vehicles"=>[
                ["id"=>1, "profile"=>"bike", "start"=>[2.3363113403320312, 48.87261892829001], "end"=>[2.3363113403320312, 48.87261892829001],]
            ]
        ], $result);
    }
}
