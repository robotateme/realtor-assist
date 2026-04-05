<?php

declare(strict_types=1);

namespace Application\Port\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface RelationsPortInterface
{
    /**
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param TDeclaringModel $model
     * @param class-string<TRelatedModel> $related
     * @return BelongsTo<TRelatedModel, TDeclaringModel>
     */
    public function belongsTo(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $ownerKey = null,
        ?string $relation = null,
    ): BelongsTo;

    /**
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param TDeclaringModel $model
     * @param class-string<TRelatedModel> $related
     * @return HasOne<TRelatedModel, TDeclaringModel>
     */
    public function hasOne(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ): HasOne;

    /**
     * @template TRelatedModel of Model
     * @template TDeclaringModel of Model
     *
     * @param TDeclaringModel $model
     * @param class-string<TRelatedModel> $related
     * @return HasMany<TRelatedModel, TDeclaringModel>
     */
    public function hasMany(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ): HasMany;
}
