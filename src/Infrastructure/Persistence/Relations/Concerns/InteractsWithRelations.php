<?php

namespace Infrastructure\Persistence\Relations\Concerns;

use Application\Port\Persistence\RelationsPort;

trait InteractsWithRelations
{
    protected function relationsPort(): RelationsPort
    {
        /** @var RelationsPort $relations */
        $relations = app(RelationsPort::class);

        return $relations;
    }
}
