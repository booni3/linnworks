<?php

declare(strict_types=1);

namespace Booni3\Linnworks\Tests\Unit;

use Booni3\Linnworks\Linnworks;
use Booni3\Linnworks\Api\Orders;
use Booni3\Linnworks\LinnworksFacade;
use Booni3\Linnworks\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class LinnworksServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__.'/../stubs/AuthorizeByApplication.json')),
        ]);

        $this->app->instance(Client::class, new Client([
            'handler' => HandlerStack::create($mock),
        ]));
    }

    public function test_service_provider_registers_linnworks_instance(): void
    {
        $this->assertInstanceOf(
            Linnworks::class,
            $this->app->make(Linnworks::class)
        );
    }

    public function test_service_provider_registers_alias(): void
    {
        $this->assertInstanceOf(
            Linnworks::class,
            $this->app->make('linnworks')
        );
    }

    public function test_config_is_published(): void
    {
        $this->assertFileExists(
            __DIR__.'/../../config/linnworks.php'
        );
    }

    public function test_facade_works(): void
    {
        $this->assertInstanceOf(
            Orders::class,
            LinnworksFacade::orders()
        );
    }
}
