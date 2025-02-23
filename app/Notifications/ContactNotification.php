<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Interfaces\CanPay;
use App\Interfaces\NotificationInterface;
use Exception;

class ContactNotification extends Notification implements NotificationInterface
{
    public static function getTypeId(): int
    {
        return 1;
    }

    public function getCost(): int
    {
        return 5;
    }

    /**
     * @throws \Exception
     */
    public function canNotify(): bool
    {
        return $this->getSender()->getCoins() >= $this->getCost();
    }

    public function cannotNotifyMessage(): string
    {
        return 'To be able to contact candidate you need '
            . $this->getCost() 
            . ' coins and you only have '
            . $this->getSender()->getCoins() . '!';
    }

    public function getSubject(): string
    {
        return sprintf(
            'Company %s has contacted you',
            $this->getSender()->getName()
        );
    }

    public function getMessage(): string
    {
        return sprintf(
            'Hello, %s! Company %s has contacted you. Have a nice day!',
            $this->getReceiver()->getName(),
            $this->getSender()->getName(),
        );
    }
}