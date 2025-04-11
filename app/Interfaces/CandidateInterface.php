<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CandidateInterface
{
    public function getId(): int;
    public function getEmail(): string;
    public function getName(): string;
    public function getDescription(): string;
    public function isHired(): bool;
    public function getStrengths(): array;
    public function getSoftSkills(): array;
    public function hasBeenContactedByUserIdBefore(int $userId): bool;
}
