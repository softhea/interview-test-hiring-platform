<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Interfaces\CanBeNotified;
use App\Interfaces\CanNotify;

class Notification
{
    public function __construct(private CanNotify $sender, private CanBeNotified $receiver)
    {

    }

    public function getCost(): int
    {
        return 0;
    }

    public function canNotify(): bool
    {
        return true;
    }

    public function cannotNotifyMessage(): string
    {
        return '';
    }

    public function getSender(): CanNotify
    {
        return $this->sender;
    }

    public function getReceiver(): CanBeNotified
    {
        return $this->receiver;
    }
}