<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CanBeHired
{
    public function isHired(): bool;
    public function hire(): void;
}
