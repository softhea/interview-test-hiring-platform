<?php

declare(strict_types=1);

namespace App\Entities;

use App\Interfaces\CandidateInterface;

class ApiCandidate implements CandidateInterface
{
    public function __construct(private array $candidate) {}

    public function getId(): int
    {
        return (int) ($this->candidate['user_id'] ?? 0);
    }

    public function getEmail(): string
    {
        return (string) ($this->candidate['user_email'] ?? '');
    }

    public function getName(): string
    {
        return (string) ($this->candidate['user_name'] ?? '');
    }

    public function getDescription(): string
    {
        return (string) ($this->candidate['user_description'] ?? '');
    }

    public function isHired(): bool
    {
        return (bool) ($this->candidate['user_is_hired'] ?? false);
    }
    
    public function getStrengths(): array
    {
        return (array) ($this->candidate['user_strengths'] ?? []);
    }

    public function getSoftSkills(): array
    {
        return (array) ($this->candidate['user_soft_skills'] ?? []);
    }

    public function hasBeenContactedByUserIdBefore(int $userId): bool
    {
        return false;
    }
}
