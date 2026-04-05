<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Criteria\Persistence;

use Application\Criteria\Persistence\Filter;
use Application\Criteria\Persistence\FilterEnum;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{
    public function test_it_exposes_filter_payload(): void
    {
        $filter = new Filter('status', FilterEnum::IN, ['new', 'active']);

        self::assertSame('status', $filter->column);
        self::assertSame(FilterEnum::IN, $filter->filter);
        self::assertSame(['new', 'active'], $filter->value);
    }
}
