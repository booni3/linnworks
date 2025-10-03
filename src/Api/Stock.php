<?php

namespace Booni3\Linnworks\Api;

class Stock extends ApiClient
{
    public function getStockConsumption(string $stockItemId = "", string $locationId = "", string $startDate = "", string $endDate = "")
    {
        return $this->get('Stock/GetStockConsumption', [
            "stockItemId" => $stockItemId,
            "locationId" => $locationId,
            "startDate" => $startDate,
            "endDate" => $endDate,
        ]);
    }

    public function getStockItems(string $keyWord = "",string $locationId = "",int $entriesPerPage = 100, int $pageNumber = 1, bool $excludeComposites = true, bool $excludeVariations = true, bool $excludeBatches = true)
    {
        return $this->get('Stock/GetStockItems', [
            "keyWord" => $keyWord,
            "locationId" => $locationId,
            "entriesPerPage" => $entriesPerPage,
            "pageNumber" => $pageNumber,
            "excludeComposites" => $excludeComposites ?: 'false',
            "excludeVariations" => $excludeVariations ?: 'false',
            "excludeBatches" => $excludeBatches ?: 'false',
        ]);
    }

    public function GetStockItemsByIds(array $StockItemIds = [])
    {
        return $this->postJson('Stock/GetStockItemsByIds', [
            'StockItemIds' => $StockItemIds
        ])['Items'] ?? [];
    }

    public function GetStockItemsFullByIds(array $stockItemIds = [], array $dataRequirements = [])
    {
        $request = [
            'stockItemIds' => $stockItemIds
        ];

        if (!empty($dataRequirements)) {
            $request['DataRequirements'] = $dataRequirements;
        }

        return $this->postJson('Stock/GetStockItemsFullByIds', [
            'request' => $request
        ])['StockItemsFullExtended'] ?? [];
    }

    public function getStockHistory(string $stockItemId, string $locationId, int $entriesPerPage = 100, int $pageNumber = 1)
    {
        return $this->get('Stock/GetItemChangesHistory', [
            "stockItemId" => $stockItemId,
            "locationId" => $locationId,
            "entriesPerPage" => $entriesPerPage,
            "pageNumber" => $pageNumber
        ]);
    }

    public function getStockItemByKey(string $locationId = "", string $key = "") : array
    {
        return $this->get('Stock/GetStockItemsByKey', [
            "stockIdentifier" => json_encode([
                "Key" => $key,
                "LocationId" => $locationId,
            ]),
        ]);
    }

    public function updateStockLevelsBySKU(array $stockLevels, string $changeSource = ''): array
    {
        foreach ($stockLevels as $index => $item) {
            $missing = array_diff(['SKU', 'LocationId', 'Level'], array_keys($item));
            if ($missing) {
                throw new \InvalidArgumentException("Missing keys in stockLevels[$index]: " . implode(', ', $missing));
            }
        }

        return $this->postJson('Stock/UpdateStockLevelsBySKU', [
            'stockLevels' => $stockLevels,
            'changeSource' => $changeSource
        ]);
    }

    public function SetStockLevel(array $stockLevels, string $changeSource = ''): array
    {
        foreach ($stockLevels as $index => $item) {
            $missing = array_diff(['SKU', 'LocationId', 'Level'], array_keys($item));
            if ($missing) {
                throw new \InvalidArgumentException("Missing keys in stockLevels[$index]: " . implode(', ', $missing));
            }
        }

        return $this->postJson('Stock/SetStockLevel', [
            'stockLevels' => $stockLevels,
            'changeSource' => $changeSource
        ]);
    }
    
    public function UpdateStockLevelsBulk(array $items = []): array
    {
        return $this->postJson('Stock/UpdateStockLevelsBulk', [
            'Items' => $items
        ]);
    }

}
