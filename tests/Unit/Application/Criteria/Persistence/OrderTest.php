<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Criteria\Persistence;

use Application\Criteria\Persistence\Order;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function test_it_uses_given_direction(): void
    {
        $order = new Order('created_at', 'DESC');

        self::assertSame('created_at', $order->field);
        self::assertSame('DESC', $order->direction);
    }

    public function test_it_defaults_to_ascending_direction(): void
    {
        $order = new Order('created_at');

        self::assertSame('ASC', $order->direction);
    }
}
