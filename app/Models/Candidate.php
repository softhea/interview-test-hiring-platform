<?php

namespace App\Models;

use App\Interfaces\CanBeHired;
use App\Interfaces\CanBeNotified;
use App\Notifications\ContactNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use phpDocumentor\Reflection\Types\Boolean;

class Candidate extends Model implements CanBeNotified, CanBeHired
{
    use HasFactory;

    public const USER_ID = 'user_id';

    protected $fillable = [
        'is_hired',
    ];

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isHired(): bool
    {
        return (bool) $this->is_hired;
    }

    public function hire(): void
    {
        $this->update(attributes: ['is_hired' => true]);
    }

    public function receivedNotifications(): HasMany
    {
        return $this->hasMany(
            Notification::class, 
            Notification::RECEIVER_USER_ID, 
            self::USER_ID
        );
    }

    public function hasBeenContactedByUserIdBefore(int $userId): bool
    {
        return $this->receivedNotifications()
            ->where(Notification::SENDER_USER_ID, $userId)
            ->where(Notification::TYPE_ID, ContactNotification::getTypeId())
            ->exists();
    }
}
