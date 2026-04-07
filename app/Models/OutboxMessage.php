<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @property string $id
 * @property string $event_class
 * @property array<string, mixed> $payload
 * @property \Illuminate\Support\Carbon $occurred_on
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
final class OutboxMessage extends Model
{
    use HasUuids;

    protected $table = 'outbox_messages';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    #[Override]
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'occurred_on' => 'immutable_datetime',
            'published_at' => 'immutable_datetime',
        ];
    }
}
