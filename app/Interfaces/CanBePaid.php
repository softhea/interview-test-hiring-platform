<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CanBePaid
{
    public function increaseCoins(int $amount): void;
}
