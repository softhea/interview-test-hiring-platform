<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CandidateRepositoryInterface
{
    /**
     * @return CandidateInterface[]
     */
    public function findAll();
}
