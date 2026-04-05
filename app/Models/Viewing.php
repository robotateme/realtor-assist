<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ViewingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Infrastructure\Persistence\Relations\Concerns\InteractsWithRelations;
use Override;

/**
 * @property int $id
 * @property int $client_id
 * @property int $property_id
 * @property \Illuminate\Support\Carbon $scheduled_at
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Client $client
 * @property-read Property $property
 *
 * @method static ViewingFactory factory($count = null, $state = [])
 */
#[Fillable(['client_id', 'property_id', 'scheduled_at', 'status'])]
final class Viewing extends Model
{
    /** @use HasFactory<ViewingFactory> */
    use HasFactory;
    use InteractsWithRelations;

    #[Override]
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'status' => 'integer',
        ];
    }

    /** @return BelongsTo<Client,$this> */
    public function client(): BelongsTo
    {
        return $this->relationsPort()->belongsTo($this, Client::class);
    }

    /** @return BelongsTo<Property,$this> */
    public function property(): BelongsTo
    {
        return $this->relationsPort()->belongsTo($this, Property::class);
    }
}
