<?php

namespace Vroom\Tests;

use Geocoder\Model\Coordinates;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Vroom\CoordinatesNormalizer;
use Vroom\Job;
use Vroom\RoutingProblem;
use Vroom\Serializer;
use Vroom\Shipment;
use Vroom\Vehicle;

class NormalizerTest extends TestCase
{
    public function setUp(): void
    {
        $this->serializer = new Serializer();
    }

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

        $result = $this->serializer->normalize($routingProblem, 'json', [ AbstractObjectNormalizer::SKIP_NULL_VALUES => true ]);

        $this->assertEquals([
            "jobs"=>[
                [
                    "id"=>1,
                    "location"=>[2.3363113403320312, 48.87261892829001],
                    "time_windows"=>[
                        [ (int) $after->format('U'), (int) $before->format('U') ]
                    ]
                ],
                [
                    "id"=>2,
                    "location"=>[2.3548507690429683, 48.86923158125418],
                    "time_windows"=>[
                        [ (int) $after->format('U'), (int) $before->format('U') ]
                    ]
                ],
                [
                    "id"=>3,
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

    public function testNormalizeVehicle()
    {
        $vehicle = new Vehicle(1);
        $vehicle->setProfile('bike');
        $vehicle->setStart(new Coordinates(48.87261892829001, 2.3363113403320312));
        $vehicle->setEnd(new Coordinates(48.86923158125418, 2.3548507690429683));

        $result = $this->serializer->normalize($vehicle);

        $this->assertEquals([
            'id' => 1,
            'profile' => 'bike',
            'start' => [
                2.3363113403320312,
                48.87261892829001,
            ],
            'end' => [
                2.3548507690429683,
                48.86923158125418,
            ],
        ], $result);
    }

    public function testNormalizeJob()
    {
        $job = new Job(1);
        $job->setLocation(new Coordinates(48.87261892829001, 2.3363113403320312));

        $result = $this->serializer->normalize($job, 'json', [ AbstractObjectNormalizer::SKIP_NULL_VALUES => true ]);

        $this->assertEquals([
            'id' => 1,
            'location' => [
                2.3363113403320312,
                48.87261892829001,
            ],
        ], $result);
    }

    public function testNormalizeShipment()
    {
        $coords = [
            new Coordinates(48.87261892829001, 2.3363113403320312),
            new Coordinates(48.86923158125418, 2.3548507690429683),
        ];

        $jobs = [];

        $now = new \DateTime();

        $after = clone $now;
        $after->modify('+5 minutes');

        $before = clone $now;
        $before->modify('+10 minutes');

        foreach ($coords as $i => $coord) {

            $job = new Job($i);
            $job->setLocation($coord);
            $job->timeWindows = [
                [
                    (int) $after->format('U'),
                    (int) $before->format('U')
                ]
            ];

            $jobs[] = $job;
        }

        $shipment = new Shipment(...$jobs);

        $result = $this->serializer->normalize($shipment, 'json', [ AbstractObjectNormalizer::SKIP_NULL_VALUES => true ]);

        $this->assertEquals([
            'pickup' => [
                'id' => 0,
                'location' => [
                    2.3363113403320312,
                    48.87261892829001,
                ],
                'time_windows' => [
                    [
                        (int) $after->format('U'),
                        (int) $before->format('U')
                    ]
                ]
            ],
            'delivery' => [
                'id' => 1,
                'location' => [
                    2.3548507690429683,
                    48.86923158125418,
                ],
                'time_windows' => [
                    [
                        (int) $after->format('U'),
                        (int) $before->format('U')
                    ]
                ]
            ]
        ], $result);
    }
}
