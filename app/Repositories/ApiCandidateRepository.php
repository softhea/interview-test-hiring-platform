<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\ApiCandidate;
use App\Interfaces\CandidateRepositoryInterface;
use GuzzleHttp\Client;

class ApiCandidateRepository implements CandidateRepositoryInterface
{
    public function findAll()
    {
        $client = new Client;
        $response = $client->get('http://api-dummy.test');
        $response = (array) json_decode(
            (string) $response->getBody()->getContents(), 
            true
        );
        if (!$response['users']) {
            return [];
        }

        $response = (array) $response['users'];
        $candidates = [];
        foreach ($response as $candidate) {
            $candidates[] = new ApiCandidate((array) $candidate);
        }

        return $candidates;
    }
}
