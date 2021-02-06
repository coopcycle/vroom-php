<?php

namespace Vroom;

use GuzzleHttp\Client as BaseClient;

class Client extends BaseClient
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    public function solve(RoutingProblem $problem)
    {
        $normalizer = new RoutingProblemNormalizer();

        return $this->request('POST', '', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($normalizer->normalize($problem)),
        ]);
    }
}
