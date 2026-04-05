<?php

declare(strict_types=1);

namespace Application\Adapter\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface CriteriaInterface
{
    public function first(): ?Model;

    /**
     * @return Collection<int, Model>
     */
    public function get(): Collection;

    /** @return LengthAwarePaginator<int, Model> */
    public function paginate(int $perPage = 15): LengthAwarePaginator;
}
