<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repositories;

use Application\Criteria\Persistence\Criteria;
use Application\Criteria\Persistence\Filter;
use Application\Criteria\Persistence\FilterEnum;
use Application\Criteria\Persistence\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Traversable;

final class EloquentFilterContext
{
    /**
     * @template TModel of Model
     *
     * @param Builder<TModel> $builder
     * @return Builder<TModel>
     */
    public function apply(Builder $builder, Criteria $criteria): Builder
    {
        $this->applyFilters($builder, $criteria->getFilters());
        $this->applyOrders($builder, $criteria->getOrders());
        $this->applyLimit($builder, $criteria->getLimit());

        return $builder;
    }

    /**
     * @template TModel of Model
     *
     * @param Builder<TModel> $builder
     * @param list<Filter> $filters
     * @return Builder<TModel>
     */
    public function applyFilters(Builder $builder, array $filters): Builder
    {
        foreach ($filters as $filter) {
            $this->applyFilter($builder, $filter);
        }

        return $builder;
    }

    /**
     * @template TModel of Model
     *
     * @param Builder<TModel> $builder
     * @param list<Order> $orders
     * @return Builder<TModel>
     */
    public function applyOrders(Builder $builder, array $orders): Builder
    {
        foreach ($orders as $order) {
            $this->applyOrder($builder, $order);
        }

        return $builder;
    }

    /**
     * @template TModel of Model
     *
     * @param Builder<TModel> $builder
     * @return Builder<TModel>
     */
    public function applyLimit(Builder $builder, ?int $limit): Builder
    {
        if ($limit !== null) {
            $builder->limit($limit);
        }

        return $builder;
    }

    /**
     * @template TModel of Model
     *
     * @param Builder<TModel> $builder
     */
    private function applyFilter(Builder $builder, Filter $filter): void
    {
        match ($filter->filter) {
            FilterEnum::EQUAL => $builder->where($filter->column, $filter->value),
            FilterEnum::NOT_EQUAL => $builder->whereNot($filter->column, $filter->value),
            FilterEnum::GREATER_THAN =>
            $builder->where($filter->column, FilterEnum::GREATER_THAN->value, $filter->value),
            FilterEnum::GREATER_OR_EQUAL =>
            $builder->where($filter->column, FilterEnum::GREATER_OR_EQUAL->value, $filter->value),
            FilterEnum::LESS_THAN =>
            $builder->where($filter->column, FilterEnum::LESS_THAN->value, $filter->value),
            FilterEnum::LESS_OR_EQUAL =>
            $builder->where($filter->column, FilterEnum::LESS_OR_EQUAL->value, $filter->value),
            FilterEnum::LIKE => $builder->where($filter->column, FilterEnum::LIKE->value, $filter->value),
            FilterEnum::IN => $builder->whereIn($filter->column, $this->iterableValue($filter->value)),
            FilterEnum::NOT_IN => $builder->whereNotIn($filter->column, $this->iterableValue($filter->value)),
        };
    }

    /**
     * @template TModel of Model
     *
     * @param Builder<TModel> $builder
     */
    public function applyOrder(Builder $builder, Order $order): void
    {
        $builder->orderBy($order->field, strtolower($order->direction));
    }

    /**
     * @param scalar|array<int, mixed>|Traversable<int, mixed>|null $value
     * @return array<int, mixed>
     */
    private function iterableValue(mixed $value): array
    {
        if ($value instanceof Traversable) {
            return array_values(iterator_to_array($value));
        }

        if (is_array($value)) {
            return array_values($value);
        }

        return [$value];
    }
}
