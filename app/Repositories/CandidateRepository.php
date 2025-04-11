<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Candidate;
use App\Interfaces\CandidateInterface;

class CandidateRepository
{
    /**
     * @return CandidateInterface[]
     */
    public function findAll()
    {
        return Candidate::with('receivedNotifications')->get();
    }
}
