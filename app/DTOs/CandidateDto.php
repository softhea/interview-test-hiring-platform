<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Interfaces\CandidateInterface;

class CandidateDto
{
    public ?int $id;
    public ?string $email;
    public ?string $name;
    public ?string $description;
    public bool $isHired;
    public bool $canBeHired;
    public array $strengths;
    public array $softSkills;

    public function setCanBeHired(bool $canBeHired): void
    {
        $this->canBeHired = $canBeHired;
    }

    public function __construct(CandidateInterface $candidate)
    {
        $this->id = $candidate->getId();
        $this->email = $candidate->getEmail();
        $this->name = $candidate->getName();
        $this->description = $candidate->getDescription();
        $this->isHired = $candidate->isHired();
        $this->strengths = $candidate->getStrengths();
        $this->softSkills = $candidate->getSoftSkills();
    }
}
