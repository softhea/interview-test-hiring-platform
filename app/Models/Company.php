<?php

namespace App\Models;

use App\Interfaces\CanBePaid;
use App\Interfaces\CanHire;
use App\Interfaces\CanNotify;
use App\Interfaces\CanPay;
use App\Notifications\ContactNotification;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model implements CanPay, CanBePaid, CanNotify, CanHire
{
    use HasFactory;

    public const USER_ID = 'user_id';

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * @throws \Exception
     */
    public function decreaseCoins(int $amount): void
    {
        if ($this->getCoins() < $amount) {
            throw new Exception(
                'Cannot decrease ' . $this->getCoins() . ' coins with ' . $amount
            );
        }

        $this->wallet()->update([
            'coins' => $this->getCoins() - $amount,
        ]);
    }

    public function increaseCoins(int $amount): void
    {
        $this->wallet()->update([
            'coins' => $this->getCoins() + $amount,
        ]);
    }

    public function getCoins(): int
    {
        return $this->wallet->coins;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function sentNotifications(): HasMany
    {
        return $this->hasMany(
            Notification::class, 
            Notification::SENDER_USER_ID, 
            self::USER_ID
        );
    }

    public function hasContactedUserIdBefore(int $userId): bool
    {
        return $this->sentNotifications()
            ->where(Notification::RECEIVER_USER_ID, $userId)
            ->where(Notification::TYPE_ID, ContactNotification::getTypeId())
            ->exists();
    }

    public function getLatestContactNotificationToUserId(int $userId): Notification
    {
        return $this->sentNotifications()
            ->where(Notification::RECEIVER_USER_ID, $userId)
            ->where(Notification::TYPE_ID, ContactNotification::getTypeId())
            ->latest()
            ->first();
    }
}
