<?php

declare(strict_types=1);

namespace Booni3\Linnworks\Tests\Unit;

use Booni3\Linnworks\Api\Stock;
use Booni3\Linnworks\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class StockApiTest extends TestCase
{
    public function test_legacy_stock_method_names_remain_available(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['Items' => [['SKU' => 'ABC']]])),
            new Response(200, [], json_encode(['StockItemsFullExtended' => [['SKU' => 'ABC']]])),
            new Response(200, [], json_encode(['ok' => true])),
            new Response(200, [], json_encode(['ok' => true])),
        ]);
        $history = [];
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(\GuzzleHttp\Middleware::history($history));
        $stock = new Stock(new Client(['handler' => $handlerStack]), 'https://example.test/api/', 'token');

        $this->assertSame([['SKU' => 'ABC']], $stock->GetStockItemsByIds(['stock-item-id']));
        $this->assertSame([['SKU' => 'ABC']], $stock->GetStockItemsFullByIds(['stock-item-id'], ['StockLevels']));
        $this->assertSame(['ok' => true], $stock->SetStockLevel([[
            'SKU' => 'ABC',
            'LocationId' => 'location-id',
            'Level' => 5,
        ]]));
        $this->assertSame(['ok' => true], $stock->UpdateStockLevelsBulk([[
            'StockItemId' => 'stock-item-id',
            'Quantity' => 5,
        ]]));

        $this->assertSame('Stock/GetStockItemsByIds', $this->requestPath($history[0]['request']));
        $this->assertSame('Stock/GetStockItemsFullByIds', $this->requestPath($history[1]['request']));
        $this->assertSame('Stock/SetStockLevel', $this->requestPath($history[2]['request']));
        $this->assertSame('Stock/UpdateStockLevelsBulk', $this->requestPath($history[3]['request']));
    }

    private function requestPath(Request $request): string
    {
        return ltrim($request->getUri()->getPath(), '/api/');
    }
}
