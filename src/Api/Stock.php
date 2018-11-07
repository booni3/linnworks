<?php

namespace Booni3\Linnworks\Api;

class Stock extends ApiClient
{
    public function getStockConsumption($stockItemId, $locationId, $startDate, $endDate)
    {
        return $this->get('Stock/GetStockConsumption', [
            "stockItemId" => $stockItemId,
            "locationId" => $locationId,
            "startDate" => $startDate,
            "endDate" => $endDate,
        ]);
    }

    public function getStockItems(string $keyWord,string $locationId,int $entriesPerPage, $pageNumber, bool $excludeComposites, bool $excludeVariations, bool $excludeBatches)
    {
        return $this->get('Stock/GetStockItems', [
            "keyWord" => $keyWord,
            "locationId" => $locationId,
            "entriesPerPage" => $entriesPerPage,
            "pageNumber" => $pageNumber,
            "excludeComposites" => $excludeComposites,
            "excludeVariations" => $excludeVariations,
            "excludeBatches" => $excludeBatches,
        ]);
    }
}