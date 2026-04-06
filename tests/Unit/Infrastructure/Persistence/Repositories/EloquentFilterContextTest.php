<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Persistence\Repositories;

use Application\Criteria\Persistence\Criteria;
use Application\Criteria\Persistence\Filter;
use Application\Criteria\Persistence\FilterEnum;
use Application\Criteria\Persistence\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class EloquentFilterContextTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_applies_equal_and_not_equal_filters(): void
    {
        $builder = $this->mockBuilder();
        $whereExpectation = $this->expect($builder, 'where');
        $whereExpectation->once()->with('status', 'active')->andReturnSelf();
        $whereNotExpectation = $this->expect($builder, 'whereNot');
        $whereNotExpectation->once()->with('archived', true)->andReturnSelf();

        $context = new EloquentFilterContext();
        $filters = [
            new Filter('status', FilterEnum::EQUAL, 'active'),
            new Filter('archived', FilterEnum::NOT_EQUAL, true),
        ];

        self::assertSame($builder, $context->applyFilters($builder, $filters));
    }

    public function test_it_applies_comparison_filters_with_correct_operators(): void
    {
        $builder = $this->mockBuilder();
        $this->expect($builder, 'where')->once()->with('price', '>', 100)->andReturnSelf();
        $this->expect($builder, 'where')->once()->with('price', '>=', 200)->andReturnSelf();
        $this->expect($builder, 'where')->once()->with('price', '<', 300)->andReturnSelf();
        $this->expect($builder, 'where')->once()->with('price', '<=', 400)->andReturnSelf();

        $context = new EloquentFilterContext();
        $filters = [
            new Filter('price', FilterEnum::GREATER_THAN, 100),
            new Filter('price', FilterEnum::GREATER_OR_EQUAL, 200),
            new Filter('price', FilterEnum::LESS_THAN, 300),
            new Filter('price', FilterEnum::LESS_OR_EQUAL, 400),
        ];

        $context->applyFilters($builder, $filters);
    }

    public function test_it_applies_like_in_and_not_in_filters(): void
    {
        $builder = $this->mockBuilder();
        $this->expect($builder, 'where')->once()->with('name', 'like', '%john%')->andReturnSelf();
        $this->expect($builder, 'whereIn')->once()->with('id', [1, 2, 3])->andReturnSelf();
        $this->expect($builder, 'whereNotIn')->once()->with('type', ['archived'])->andReturnSelf();

        $context = new EloquentFilterContext();
        $filters = [
            new Filter('name', FilterEnum::LIKE, '%john%'),
            new Filter('id', FilterEnum::IN, [1, 2, 3]),
            new Filter('type', FilterEnum::NOT_IN, ['archived']),
        ];

        $context->applyFilters($builder, $filters);
    }

    public function test_it_normalizes_iterable_values_for_in_filters(): void
    {
        $builder = $this->mockBuilder();
        $this->expect($builder, 'whereIn')->once()->with('id', [5, 7])->andReturnSelf();

        $context = new EloquentFilterContext();
        $filters = [
            new Filter('id', FilterEnum::IN, new \ArrayIterator([5, 7])),
        ];

        $context->applyFilters($builder, $filters);
    }

    public function test_it_applies_order_in_lowercase(): void
    {
        $builder = $this->mockBuilder();
        $this->expect($builder, 'orderBy')->once()->with('created_at', 'desc')->andReturnSelf();

        $context = new EloquentFilterContext();

        $context->applyOrder($builder, new Order('created_at', 'DESC'));
    }

    public function test_it_applies_complete_criteria(): void
    {
        $builder = $this->mockBuilder();
        $this->expect($builder, 'where')->once()->with('status', 'active')->andReturnSelf();
        $this->expect($builder, 'orderBy')->once()->with('created_at', 'desc')->andReturnSelf();
        $this->expect($builder, 'limit')->once()->with(5)->andReturnSelf();

        $context = new EloquentFilterContext();
        $criteria = new Criteria(
            filters: [new Filter('status', FilterEnum::EQUAL, 'active')],
            orders: [new Order('created_at', 'DESC')],
            limit: 5,
        );

        self::assertSame($builder, $context->apply($builder, $criteria));
    }

    public function test_it_skips_limit_when_not_defined(): void
    {
        $builder = $this->mockBuilder();

        $context = new EloquentFilterContext();

        self::assertSame($builder, $context->applyLimit($builder, null));
    }

    /**
     * @return Builder<Model>&MockInterface
     */
    private function mockBuilder(): Builder
    {
        /** @var Builder<Model>&MockInterface $builder */
        $builder = Mockery::mock(Builder::class);

        return $builder;
    }

    /**
     * @param Builder<Model>&MockInterface $builder
     */
    private function expect(Builder $builder, string $method): mixed
    {
        return $builder->shouldReceive($method);
    }
}
