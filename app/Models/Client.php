<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Infrastructure\Persistence\Relations\Concerns\InteractsWithRelations;

/**
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Viewing> $viewings
 * @property-read DialogSession|null $dialogSession
 *
 * @method static ClientFactory factory($count = null, $state = [])
 */
#[Fillable(['full_name', 'email', 'phone', 'user_id'])]
final class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;
    use InteractsWithRelations;

    /** @return BelongsTo<User,$this> */
    public function user(): BelongsTo
    {
        return $this->relationsPort()->belongsTo($this, User::class);
    }

    /** @return HasMany<Viewing,$this> */
    public function viewings(): HasMany
    {
        return $this->relationsPort()->hasMany($this, Viewing::class);
    }

    /** @return HasOne<DialogSession,$this> */
    public function dialogSession(): HasOne
    {
        return $this->relationsPort()->hasOne($this, DialogSession::class);
    }
}
