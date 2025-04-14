<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Candidate;
use App\Interfaces\CandidateRepositoryInterface;

class CandidateRepository implements CandidateRepositoryInterface
{
    public function findAll()
    {
        return Candidate::with('receivedNotifications')->get();
    }
}
