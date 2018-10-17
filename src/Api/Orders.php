<?php

namespace Booni3\Linnworks\Api;

class Orders extends Api
{
    public function getOpenOrders($entriesPerPage, $pageNumber, $filters, $sorting, $fulfilmentCenter, $additionalFilters)
    {
        return $this->_get('Orders/GetOpenOrders', [
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
        return $this->_get('Orders/GetAllOpenOrders', [
            "filters" => $filters,
            "sorting" => $sorting,
            "fulfilmentCenter" => $fulfilmentCenter,
            "additionalFilter" => $additionalFilter
        ]);
    }

    public function GetOrdersById(array $pkOrderIds)
    {
        return $this->_get('Orders/GetOrdersById', [
            "pkOrderIds" => json_encode($pkOrderIds)
        ]);
    }

    public function MoveToLocation(array $orderIds, $pkStockLocationId)
    {
        return $this->_get('Orders/MoveToLocation', [
            "orderIds" => json_encode($orderIds),
            "pkStockLocationId" => $pkStockLocationId
        ]);
    }

    public function ChangeShippingMethod(array $orderIds, $shippingMethod)
    {
        return $this->_get('Orders/ChangeShippingMethod', [
            "orderIds" => json_encode($orderIds),
            "shippingMethod" => $shippingMethod
        ]);
    }

    public function SetLabelsPrinted(array $orderIds)
    {
        return $this->_post('Orders/SetLabelsPrinted', [
            "orderIds" => json_encode($orderIds)
        ]);
    }

    public function setShippingInfo($orderId, array $info)
    {
        return $this->_post('Orders/SetOrderShippingInfo',[
            'orderId' => $orderId,
            'info' => json_encode($info)
        ]);
    }

    public function processFulfilmentCentreOrder($orderId, array $info)
    {
        return $this->_post('Orders/ProcessFulfilmentCentreOrder',[
            'orderId' => $orderId,
            'info' => json_encode($info)
        ]);
    }

}