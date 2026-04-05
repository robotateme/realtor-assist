<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Criteria\Persistence;

use Application\Criteria\Persistence\Criteria;
use Application\Criteria\Persistence\Filter;
use Application\Criteria\Persistence\FilterEnum;
use Application\Criteria\Persistence\Order;
use PHPUnit\Framework\TestCase;

final class CriteriaTest extends TestCase
{
    public function test_it_exposes_filters_orders_and_limit(): void
    {
        $filters = [
            new Filter('price', FilterEnum::GREATER_OR_EQUAL, 100000),
        ];
        $orders = [
            new Order('created_at', 'DESC'),
        ];

        $criteria = new Criteria($filters, $orders, 10);

        self::assertSame($filters, $criteria->getFilters());
        self::assertSame($orders, $criteria->getOrders());
        self::assertSame(10, $criteria->getLimit());
    }

    public function test_it_uses_empty_defaults(): void
    {
        $criteria = new Criteria();

        self::assertSame([], $criteria->getFilters());
        self::assertSame([], $criteria->getOrders());
        self::assertNull($criteria->getLimit());
    }
}
