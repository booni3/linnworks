<?php

namespace Booni3\Linnworks\Api;

class Orders extends ApiClient
{
    public function getOpenOrders($entriesPerPage, $pageNumber, $filters, $sorting, $fulfilmentCenter, $additionalFilters)
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

    public function getAllOpenOrders($filters, $sorting, $fulfilmentCenter, $additionalFilter)
    {
        return $this->get('Orders/GetAllOpenOrders', [
            "filters" => $filters,
            "sorting" => $sorting,
            "fulfilmentCenter" => $fulfilmentCenter,
            "additionalFilter" => $additionalFilter
        ]);
    }

    public function GetOrdersById(array $pkOrderIds)
    {
        return $this->get('Orders/GetOrdersById', [
            "pkOrderIds" => json_encode($pkOrderIds)
        ]);
    }

    public function MoveToLocation(array $orderIds, $pkStockLocationId)
    {
        return $this->get('Orders/MoveToLocation', [
            "orderIds" => json_encode($orderIds),
            "pkStockLocationId" => $pkStockLocationId
        ]);
    }

    public function ChangeShippingMethod(array $orderIds, $shippingMethod)
    {
        return $this->get('Orders/ChangeShippingMethod', [
            "orderIds" => json_encode($orderIds),
            "shippingMethod" => $shippingMethod
        ]);
    }

    public function SetLabelsPrinted(array $orderIds)
    {
        return $this->post('Orders/SetLabelsPrinted', [
            "orderIds" => json_encode($orderIds)
        ]);
    }

    public function setShippingInfo($orderId, array $info)
    {
        return $this->post('Orders/SetOrderShippingInfo',[
            'orderId' => $orderId,
            'info' => json_encode($info)
        ]);
    }

    public function processOrder($orderId, $scanPerformed, $locationId, $allowZeroAndNegativeBatchQty)
    {
        return $this->post('Orders/ProcessOrder',[
            'orderId' => $orderId,
            'scanPerformed' => $scanPerformed,
            'locationId' => $locationId,
            'allowZeroAndNegativeBatchQty' => $allowZeroAndNegativeBatchQty,
        ]);
    }

    public function processFulfilmentCentreOrder($orderId)
    {
        return $this->post('Orders/ProcessFulfilmentCentreOrder',[
            'orderId' => $orderId
        ]);
    }
}