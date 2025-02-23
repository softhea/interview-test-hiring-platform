<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CanBePaid;
use App\Interfaces\CanPay;

class PaymentService
{
    public function debit(CanPay $client, int $amount): void
    {
        $client->decreaseCoins($amount);
    }

    public function credit(CanBePaid $client, int $amount): void
    {
        $client->increaseCoins($amount);
    }
}
