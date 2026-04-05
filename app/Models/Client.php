<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Infrastructure\Persistence\Relations\Concerns\InteractsWithRelations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

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

    public function user(): BelongsTo
    {
        return $this->relationsPort()->belongsTo($this, User::class);
    }

    public function viewings(): HasMany
    {
        return $this->relationsPort()->hasMany($this, Viewing::class);
    }

    public function dialogSession(): HasOne
    {
        return $this->relationsPort()->hasOne($this, DialogSession::class);
    }
}
