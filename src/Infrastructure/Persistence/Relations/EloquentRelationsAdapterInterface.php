<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Relations;

use Application\Port\Persistence\RelationsPortInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Override;

final class EloquentRelationsAdapterInterface implements RelationsPortInterface
{
    /**
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param TDeclaringModel $model
     * @param class-string<TRelatedModel> $related
     * @return BelongsTo<TRelatedModel, TDeclaringModel>
     */
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

    /**
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param TDeclaringModel $model
     * @param class-string<TRelatedModel> $related
     * @return HasOne<TRelatedModel, TDeclaringModel>
     */
    #[Override]
    public function hasOne(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ): HasOne {
        return $model->hasOne($related, $foreignKey, $localKey);
    }

    /**
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param TDeclaringModel $model
     * @param class-string<TRelatedModel> $related
     * @return HasMany<TRelatedModel, TDeclaringModel>
     */
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
