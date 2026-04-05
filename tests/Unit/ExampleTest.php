<?php

declare(strict_types=1);

namespace Tests\Unit;

use Domain\Property\TypesEnum;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_property_types_enum_contains_expected_value(): void
    {
        self::assertSame('house', TypesEnum::HOUSE->value);
    }
}
