<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PropertyFactory;
use Domain\Property\TypesEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

/**
 * @property int $id
 * @property string $title
 * @property string $location
 * @property int $price
 * @property TypesEnum $type
 * @property int $availability_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Viewing> $viewings
 *
 * @method static PropertyFactory factory($count = null, $state = [])
 */
#[Fillable(['title', 'location', 'price', 'type', 'availability_status'])]
final class Property extends Model
{
    /** @use HasFactory<PropertyFactory> */
    use HasFactory;

    #[Override]
    protected function casts(): array
    {
        return [
            'type' => TypesEnum::class,
            'price' => 'integer',
            'availability_status' => 'integer',
        ];
    }

    /** @return HasMany<Viewing,$this> */
    public function viewings(): HasMany
    {
        return $this->hasMany(Viewing::class);
    }
}
