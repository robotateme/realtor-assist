<?php

namespace Application\Port\Persistence;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface RelationsPort
{
    /**
     * @param Model $model
     * @param class-string<Model> $related
     */
    public function belongsTo(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $ownerKey = null,
        ?string $relation = null,
    ): BelongsTo;

    /**
     * @param Model $model
     * @param class-string<Model> $related
     */
    public function hasOne(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ): HasOne;

    /**
     * @param Model $model
     * @param class-string<Model> $related
     */
    public function hasMany(
        Model $model,
        string $related,
        ?string $foreignKey = null,
        ?string $localKey = null,
    ): HasMany;
}
