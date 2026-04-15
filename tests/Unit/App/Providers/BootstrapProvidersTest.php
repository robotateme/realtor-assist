<?php

declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use PHPUnit\Framework\TestCase;

final class BootstrapProvidersTest extends TestCase
{
    public function test_it_registers_repository_and_integrations_service_providers(): void
    {
        /** @var list<class-string> $providers */
        $providers = require __DIR__ . '/../../../../bootstrap/providers.php';

        self::assertContains(\App\Providers\RepositoryServiceProvider::class, $providers);
        self::assertContains(\App\Providers\IntegrationsServiceProvider::class, $providers);
    }
}
