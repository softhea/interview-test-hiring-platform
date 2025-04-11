<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CandidateDto;
use App\Repositories\CandidateRepository;

class CandidateService
{
    private array $desiredSoftSkills = [];

    public function getDesiredSoftSkills(): array
    {
        return $this->desiredSoftSkills;
    }

    public function __construct(private CandidateRepository $candidateRepository) {}

    /**
     * @return CandidateDto[]
     */
    public function list(int $senderUserId): array
    {
        $candidates = $this->candidateRepository->findAll();
        
        $candidateDtos = [];
        foreach ($candidates as $candidate) {
            foreach ($candidate->getSoftSkills() as $softSkill) {
                $this->desiredSoftSkills[] = $softSkill;
            }

            $candidateDto = new CandidateDto(candidate: $candidate);
            $candidateDto->setCanBeHired(
                !$candidate->isHired() 
                && $candidate->hasBeenContactedByUserIdBefore($senderUserId)
            );

            $candidateDtos[] = $candidateDto;
        }
        $this->desiredSoftSkills = array_unique($this->desiredSoftSkills);
        $this->desiredSoftSkills = array_slice($this->desiredSoftSkills, 0, 2);

        return $candidateDtos;
    }
}
