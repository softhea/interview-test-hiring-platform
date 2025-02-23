<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CanBeNotified
{
    public function getUserId(): int;
    public function getName(): string;
    public function getEmail(): string;
}
