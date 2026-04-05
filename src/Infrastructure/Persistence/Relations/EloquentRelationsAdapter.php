<?php

namespace Infrastructure\Persistence\Relations;

use Application\Port\Persistence\RelationsPort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Override;

final class EloquentRelationsAdapter implements RelationsPort
{
    #[Override]
    public function belongsTo(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $ownerKey = null,
        ?string $relation = null,
    ): BelongsTo {
        return $model->belongsTo($related, $foreignKey, $ownerKey, $relation);
    }

    #[Override]
    public function hasOne(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ): HasOne {
        return $model->hasOne($related, $foreignKey, $localKey);
    }

    #[Override]
    public function hasMany(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ): HasMany {
        return $model->hasMany($related, $foreignKey, $localKey);
    }
}
