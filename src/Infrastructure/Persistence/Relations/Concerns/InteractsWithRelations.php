<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Relations\Concerns;

use Application\Port\Persistence\RelationsPortInterface;

trait InteractsWithRelations
{
    protected function relationsPort(): RelationsPortInterface
    {
        /** @var RelationsPortInterface $relations */
        $relations = app(RelationsPortInterface::class);

        return $relations;
    }
}
