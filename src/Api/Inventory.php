<?php

namespace Booni3\Linnworks\Api;

class Inventory extends ApiClient
{
    public function GetCountries()
    {
        return $this->get('Inventory/GetCountries');
    }

    public function GetChannels()
    {
        return $this->get('Inventory/GetChannels');
    }

    public function GetStockItemIdsBySKU(array $skus): array
    {
        return $this->postJson('Inventory/GetStockItemIdsBySKU', [
            'request' => [
                'SKUS' => $skus
            ]
        ])['Items'] ?? [];
    }

    public function GetInventoryItemById(string $id = '')
    {
        return $this->get('Inventory/GetInventoryItemById', compact('id'));
    }

    public function GetInventoryItemsCompositionByIds(array $ids): array
    {
        return $this->postJson('Inventory/GetInventoryItemsCompositionByIds', [
            'request' => [
                'InventoryItemIds' => $ids
            ]
        ])['InventoryItemsCompositionByIds'] ?? [];
    }

    public function GetInventoryItemCompositions(string $inventoryItemId = '', bool $getFullDetail = false)
    {
        return $this->get('Inventory/GetInventoryItemCompositions', [
            'inventoryItemId' => $inventoryItemId,
            'getFullDetail' => $getFullDetail
        ]);
    }

    public function CreateInventoryItemCompositions(string $parentStockItemId, array $childComponents)
    {
        $payload = $this->buildCompositionPayload($parentStockItemId, $childComponents);
        return $this->postJson('Inventory/CreateInventoryItemCompositions', $payload);
    }

    public function UpdateInventoryItemCompositions(string $parentStockItemId, array $childComponents)
    {
        $payload = $this->buildCompositionPayload($parentStockItemId, $childComponents);
        return $this->postJson('Inventory/UpdateInventoryItemCompositions', $payload);
    }

    public function DeleteInventoryItemCompositions(string $stockItemId, array $compositionIds)
    {
        return $this->postJson('Inventory/DeleteInventoryItemCompositions', [
            'stockItemId' => $stockItemId,
            'inventoryItemCompositionIds' => $compositionIds
        ]);
    }

    private function buildCompositionPayload(string $parentStockItemId, array $childComponents): array
    {
        if (empty($childComponents)) {
            throw new \Exception('At least one child component is required');
        }

        $payload = ['inventoryItemCompositions' => []];

        foreach ($childComponents as $component) {
            $payload['inventoryItemCompositions'][] = [
                'StockItemId' => $parentStockItemId,
                'LinkedStockItemId' => $component['LinkedStockItemId'],
                'Quantity' => (int)$component['Quantity'],
            ];
        }

        return $payload;
    }
}