<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['client_id', 'provider', 'username', 'first_name', 'last_name', 'is_bot', 'messenger_id'])]
final class MessengerClient extends Model
{
    protected $fillable = [
        'client_id',
        'provider',
        'username',
        'first_name',
        'last_name',
        'is_bot',
        'messenger_id',
    ];
}
