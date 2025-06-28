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

    /////////////////////////////////
    // Get Inventory Items
    /////////////////////////////////

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
        return $this->get('Inventory/GetInventoryItemById',
            compact('id')
        );

    }

    /////////////////////////////////
    // Inventory Item Compositions
    /////////////////////////////////

    public function GetInventoryItemsCompositionByIds(array $ids): array
    {
        return $this->postJson('Inventory/GetInventoryItemsCompositionByIds', [
            'request' => [
                'InventoryItemIds' => $ids
            ]
        ])['InventoryItemsCompositionByIds'] ?? [];
    }

    public function GetInventoryItemCompositions(string $inventoryItemId = '', $getFullDetail = 'false')
    {
        return $this->get('Inventory/GetInventoryItemCompositions', [
            'inventoryItemId' => $inventoryItemId,
            'getFullDetail' => true
        ]);

    }

    public function CreateInventoryItemCompositions($parentStockItemId, $childComponents)
    {
        if (empty($childComponents)) {
            throw new \Exception('At least one child component is required');
        }

        $payload = [
            'inventoryItemCompositions' => []
        ];

        foreach ($childComponents as $component) {
            // Build composition entry
            $compositionItem = [
                'StockItemId' => $parentStockItemId, // Your existing parent desk
                'LinkedStockItemId' => $component['LinkedStockItemId'], // Child component to link
                'Quantity' => (int)$component['Quantity'], // How many of this component
            ];

            $payload['inventoryItemCompositions'][] = $compositionItem;
        }

        return $this->postJson('Inventory/CreateInventoryItemCompositions', $payload);
    }


    public function UpdateInventoryItemCompositions($parentStockItemId, $childComponents)
    {
        if (empty($childComponents)) {
            throw new \Exception('At least one child component is required');
        }

        $payload = [
            'inventoryItemCompositions' => []
        ];

        foreach ($childComponents as $component) {
            // Build composition entry
            $compositionItem = [
                'StockItemId' => $parentStockItemId, // Your existing parent desk
                'LinkedStockItemId' => $component['LinkedStockItemId'], // Child component to link
                'Quantity' => (int)$component['Quantity'], // How many of this component
            ];

            $payload['inventoryItemCompositions'][] = $compositionItem;
        }

        return $this->postJson('Inventory/UpdateInventoryItemCompositions', $payload);
    }

    public function DeleteInventoryItemCompositions(string $stockItemId, array $compositionIds)
    {
        return $this->postJson('Inventory/DeleteInventoryItemCompositions', [
            'stockItemId' => $stockItemId,
            'inventoryItemCompositionIds' => $compositionIds
        ]);
    }

}