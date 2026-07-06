<?php

declare(strict_types=1);

namespace Booni3\Linnworks\Tests\Unit;

use Booni3\Linnworks\Api\Inventory;
use Booni3\Linnworks\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class InventoryApiTest extends TestCase
{
    public function test_inventory_item_methods_use_linnworks_payload_shapes(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'Results' => [
                    ['Result' => 'stock-item-id', 'ResultStatus' => 'SUCCESSFUL', 'Message' => ''],
                ],
                'ResultStatus' => 'SUCCESSFUL',
            ])),
            new Response(200, [], json_encode(['StockItemId' => 'stock-item-id'])),
            new Response(200, [], json_encode(['ok' => true])),
        ]);
        $history = [];
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(Middleware::history($history));

        $inventory = new Inventory(new Client(['handler' => $handlerStack]), 'https://example.test/api/', 'token');

        $bulkResponse = $inventory->AddInventoryItemBulk([[
            'ItemNumber' => 'SKU-1',
            'ItemTitle' => 'Test SKU',
            'BarcodeNumber' => '5061020500000',
        ]]);
        $getResponse = $inventory->GetInventoryItem('SKU-1');
        $singleResponse = $inventory->AddInventoryItem([
            'ItemNumber' => 'SKU-2',
            'ItemTitle' => 'Second SKU',
        ]);

        $this->assertSame('SUCCESSFUL', $bulkResponse['ResultStatus']);
        $this->assertSame('stock-item-id', $getResponse['StockItemId']);
        $this->assertSame(['ok' => true], $singleResponse);

        $this->assertSame('Inventory/AddInventoryItemBulk', $this->requestPath($history[0]['request']));
        $this->assertSame([
            'request' => [
                'InventoryItems' => [[
                    'ItemNumber' => 'SKU-1',
                    'ItemTitle' => 'Test SKU',
                    'BarcodeNumber' => '5061020500000',
                ]],
            ],
        ], json_decode((string) $history[0]['request']->getBody(), true));

        $this->assertSame('Inventory/GetInventoryItem', $this->requestPath($history[1]['request']));

        $this->assertSame('Inventory/AddInventoryItem', $this->requestPath($history[2]['request']));
        $this->assertSame([
            'inventoryItem' => [
                'ItemNumber' => 'SKU-2',
                'ItemTitle' => 'Second SKU',
            ],
        ], json_decode((string) $history[2]['request']->getBody(), true));
    }

    private function requestPath(Request $request): string
    {
        return ltrim($request->getUri()->getPath(), '/api/');
    }
}
