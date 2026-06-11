<?php

declare(strict_types=1);

namespace Booni3\Linnworks\Api;

use InvalidArgumentException;

class Stock extends ApiClient
{
    public function getStockConsumption(
        string $stockItemId = "",
        string $locationId = "",
        string $startDate = "",
        string $endDate = ""
    ): array {
        return $this->get('Stock/GetStockConsumption', [
            "stockItemId" => $stockItemId,
            "locationId" => $locationId,
            "startDate" => $startDate,
            "endDate" => $endDate,
        ]);
    }

    public function getStockItems(
        string $keyWord = "",
        string $locationId = "",
        int $entriesPerPage = 100,
        int $pageNumber = 1,
        bool $excludeComposites = true,
        bool $excludeVariations = true,
        bool $excludeBatches = true
    ): array {
        return $this->get('Stock/GetStockItems', [
            "keyWord" => $keyWord,
            "locationId" => $locationId,
            "entriesPerPage" => $entriesPerPage,
            "pageNumber" => $pageNumber,
            "excludeComposites" => $excludeComposites ? 'true' : 'false',
            "excludeVariations" => $excludeVariations ? 'true' : 'false',
            "excludeBatches" => $excludeBatches ? 'true' : 'false',
        ]);
    }

    public function getStockItemsByIds(array $stockItemIds = []): array
    {
        $response = $this->postJson('Stock/GetStockItemsByIds', [
            'StockItemIds' => $stockItemIds,
        ]);

        return $response['Items'] ?? [];
    }

    public function GetStockItemsFullByIds(array $stockItemIds = [], array $dataRequirements = []): array
    {
        $request = [
            'stockItemIds' => $stockItemIds,
        ];

        if (! empty($dataRequirements)) {
            $request['DataRequirements'] = $dataRequirements;
        }

        return $this->postJson('Stock/GetStockItemsFullByIds', [
            'request' => $request,
        ])['StockItemsFullExtended'] ?? [];
    }

    public function getStockHistory(
        string $stockItemId,
        string $locationId,
        int $entriesPerPage = 100,
        int $pageNumber = 1
    ): array {
        return $this->get('Stock/GetItemChangesHistory', [
            "stockItemId" => $stockItemId,
            "locationId" => $locationId,
            "entriesPerPage" => $entriesPerPage,
            "pageNumber" => $pageNumber,
        ]);
    }

    public function getStockItemByKey(string $locationId = "", string $key = ""): array
    {
        return $this->get('Stock/GetStockItemsByKey', [
            "stockIdentifier" => json_encode([
                "Key" => $key,
                "LocationId" => $locationId,
            ]),
        ]);
    }

    /**
     * @param array<int, array{SKU: string, LocationId: string, Level: int}> $stockLevels
     * @throws InvalidArgumentException
     */
    private function validateStockLevels(array $stockLevels): void
    {
        foreach ($stockLevels as $index => $item) {
            $missing = array_diff(['SKU', 'LocationId', 'Level'], array_keys($item));
            if (! empty($missing)) {
                throw new InvalidArgumentException(
                    "Missing keys in stockLevels[$index]: ".implode(', ', $missing)
                );
            }
        }
    }

    /**
     * @param array<int, array{SKU: string, LocationId: string, Level: int}> $stockLevels
     * @throws InvalidArgumentException
     */
    public function updateStockLevelsBySKU(array $stockLevels, string $changeSource = ''): array
    {
        $this->validateStockLevels($stockLevels);

        return $this->postJson('Stock/UpdateStockLevelsBySKU', [
            'stockLevels' => $stockLevels,
            'changeSource' => $changeSource,
        ]);
    }

    /**
     * @param array<int, array{SKU: string, LocationId: string, Level: int}> $stockLevels
     * @throws InvalidArgumentException
     */
    public function setStockLevel(array $stockLevels, string $changeSource = ''): array
    {
        $this->validateStockLevels($stockLevels);

        return $this->postJson('Stock/SetStockLevel', [
            'stockLevels' => $stockLevels,
            'changeSource' => $changeSource,
        ]);
    }

    public function UpdateStockLevelsBulk(array $items = []): array
    {
        return $this->postJson('Stock/UpdateStockLevelsBulk', [
            'Items' => $items,
        ]);
    }
}
