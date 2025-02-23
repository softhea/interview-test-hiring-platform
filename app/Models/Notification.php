<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public const TYPE_ID = 'type_id';
    public const SENDER_USER_ID = 'sender_user_id';
    public const RECEIVER_USER_ID = 'receiver_user_id';
    public const SUBJECT = 'subject';
    public const MESSAGE = 'message';

    protected $fillable = [
        self::TYPE_ID,
        self::SENDER_USER_ID,
        self::RECEIVER_USER_ID,
        self::SUBJECT,
        self::MESSAGE,
    ];
}
