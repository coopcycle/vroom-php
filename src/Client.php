<?php

namespace Vroom;

use GuzzleHttp\Client as BaseClient;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class Client extends BaseClient
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->serializer = new Serializer();
    }

    public function solve(RoutingProblem $problem)
    {
        return $this->request('POST', '', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $this->serializer->serialize($problem, 'json', [
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true
            ]),
        ]);
    }
}
