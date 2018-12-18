<?php

namespace Booni3\Linnworks\Api;

class Orders extends ApiClient
{
    public function getOpenOrders($fulfilmentCenter, int $entriesPerPage = 25, int $pageNumber = 1, string $filters = "", array $sorting = [], string $additionalFilters = "")
    {
        return $this->get('Orders/GetOpenOrders', [
            "entriesPerPage" => $entriesPerPage,
            "pageNumber" => $pageNumber,
            "filters" => $filters,
            "sorting" => $sorting,
            "fulfilmentCenter" => $fulfilmentCenter,
            "additionalFilters" => $additionalFilters
        ]);
    }

    public function getAllOpenOrders($fulfilmentCenter, string $filters = "", array $sorting = [], string $additionalFilter = "")
    {
        return $this->get('Orders/GetAllOpenOrders', [
            "filters" => $filters,
            "sorting" => $sorting,
            "fulfilmentCenter" => $fulfilmentCenter,
            "additionalFilter" => $additionalFilter
        ]);
    }

    public function GetOrdersById(array $pkOrderIds = [])
    {
        return $this->get('Orders/GetOrdersById', [
            "pkOrderIds" => json_encode($pkOrderIds)
        ]);
    }

    public function MoveToLocation(array $orderIds = [], string $pkStockLocationId = "")
    {
        return $this->get('Orders/MoveToLocation', [
            "orderIds" => json_encode($orderIds),
            "pkStockLocationId" => $pkStockLocationId
        ]);
    }

    public function ChangeShippingMethod(array $orderIds = [], string $shippingMethod = "")
    {
        return $this->get('Orders/ChangeShippingMethod', [
            "orderIds" => json_encode($orderIds),
            "shippingMethod" => $shippingMethod
        ]);
    }

    public function SetLabelsPrinted(array $orderIds = [])
    {
        return $this->post('Orders/SetLabelsPrinted', [
            "orderIds" => json_encode($orderIds)
        ]);
    }

    public function setShippingInfo(string $orderId = "", array $info = [])
    {
        return $this->post('Orders/SetOrderShippingInfo',[
            'orderId' => $orderId,
            'info' => json_encode($info)
        ]);
    }

    public function processOrder(string $orderId = "", bool $scanPerformed = true, string $locationId = "", bool $allowZeroAndNegativeBatchQty = true)
    {
        return $this->post('Orders/ProcessOrder',[
            'orderId' => $orderId,
            'scanPerformed' => $scanPerformed,
            'locationId' => $locationId,
            'allowZeroAndNegativeBatchQty' => $allowZeroAndNegativeBatchQty,
        ]);
    }

    public function processFulfilmentCentreOrder(string $orderId = "")
    {
        return $this->post('Orders/ProcessFulfilmentCentreOrder',[
            'orderId' => $orderId
        ]);
    }
}