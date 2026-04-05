<?php

namespace App\Models;

use Database\Factories\DialogSessionFactory;
use Infrastructure\Persistence\Relations\Concerns\InteractsWithRelations;
use Override;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $client_id
 * @property int $current_intent
 * @property array<string, mixed>|null $context_data
 * @property string|null $last_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Client $client
 *
 * @method static DialogSessionFactory factory($count = null, $state = [])
 */
#[Fillable(['client_id', 'current_intent', 'context_data', 'last_message'])]
final class DialogSession extends Model
{
    /** @use HasFactory<DialogSessionFactory> */
    use HasFactory;
    use InteractsWithRelations;

    #[Override]
    protected function casts(): array
    {
        return [
            'context_data' => 'array',
            'current_intent' => 'integer',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->relationsPort()->belongsTo($this, Client::class);
    }
}
