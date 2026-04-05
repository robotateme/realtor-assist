<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Property;

use Domain\Property\TypesEnum;
use PHPUnit\Framework\TestCase;

final class TypesEnumTest extends TestCase
{
    public function test_it_contains_expected_backed_values(): void
    {
        self::assertSame('apartment', TypesEnum::APARTMENT->value);
        self::assertSame('investment', TypesEnum::INVESTMENT->value);
        self::assertCount(13, TypesEnum::cases());
    }
}
