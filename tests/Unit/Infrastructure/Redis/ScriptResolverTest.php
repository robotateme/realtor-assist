<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Redis;

use Illuminate\Contracts\Redis\Factory;
use Illuminate\Redis\Connections\Connection;
use Infrastructure\Redis\LuaScript;
use Infrastructure\Redis\ScriptResolver;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ScriptResolverTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_falls_back_to_eval_when_script_is_missing(): void
    {
        /** @var Factory&MockInterface $factory */
        $factory = Mockery::mock(Factory::class);
        /** @var Connection&MockInterface $connection */
        $connection = Mockery::mock(Connection::class);
        /** @var LuaScript&MockInterface $script */
        $script = Mockery::mock(LuaScript::class);

        $script->shouldReceive('body')->once()->andReturn('return 1');
        $factory->shouldReceive('connection')->once()->with('cache')->andReturn($connection);

        /** @var Expectation $evalShaExpectation */
        $evalShaExpectation = $connection->shouldReceive('evalsha');
        $evalShaExpectation
            ->once()
            ->with('return 1', 1, 'bucket', '42')
            ->andThrow(new RuntimeException('NOSCRIPT No matching script. Please use EVAL.'));

        /** @var Expectation $evalExpectation */
        $evalExpectation = $connection->shouldReceive('eval');
        $evalExpectation
            ->once()
            ->with('return 1', 1, 'bucket', '42')
            ->andReturn([1]);

        $resolver = new ScriptResolver($factory, 'cache');

        self::assertSame([1], $resolver->execute($script, ['bucket'], ['42']));
    }
}
