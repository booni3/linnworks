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

    public function GetOrdersByNumOrderId(int $numOrderId = 1)
    {
        return $this->get('Orders/GetOrderDetailsByNumOrderId', [
            "OrderId" => $numOrderId
        ]);
    }

    public function SearchProcessedOrdersPaged(int $pageNum = 1, int $numEntriesPerPage = 50, string $from = "", string $to = "", string $dateType = "PROCESSED", string $searchField = "", string $exactMatch = "false", string $searchTerm = "")
    {
        return $this->get('ProcessedOrders/SearchProcessedOrdersPaged', [
            "from" => $from,
            "to" => $to,
            "dateType" => $dateType,
            "searchField" => $searchField,
            "exactMatch" => $exactMatch,
            "searchTerm" => $searchTerm,
            "pageNum" => $pageNum,
            "numEntriesPerPage" => $numEntriesPerPage,
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

    public function createOrder(OrderItems $orderItems,
                                string $locationName = "",
                                string $matchPostalServiceTag = "",
                                string $source = "",
                                string $subSource = "",
                                string $referenceNumber = "",
                                string $externalReference = "",
                                string $receivedDate = "",
                                string $dispatchBy = "",
                                string $currency = "",
                                string $channelBuyerName = "",
                                string $deliveryEmailAddress = "",
                                string $deliveryAddress1 = "",
                                string $deliveryAddress2 = "",
                                string $deliveryAddress3 = "",
                                string $deliveryTown = "",
                                string $deliveryRegion = "",
                                string $deliveryPostCode = "",
                                string $deliveryCountryName = "",
                                string $deliveryFullName = "",
                                string $deliveryCompanyName = "",
                                string $deliveryPhoneNumber = "",
                                string $deliveryIso3CountryCode = "",
                                string $billingAddress1 = "",
                                string $billingAddress2 = "",
                                string $billingAddress3 = "",
                                string $billingTown = "",
                                string $billingRegion = "",
                                string $billingPostCode = "",
                                string $billingCountryName = "",
                                string $billingFullName = "",
                                string $billingCompanyName = "",
                                string $billingPhoneNumber = "",
                                string $billingIso3CountryCode = "",
                                float $shippingCost = 0,
                                int $shippingTaxRate = 20,
                                string $mappingSource = "",
                                string $orderState = "None",
                                string $paymentStatus = "Paid",
                                string $paidDate = "Today"
    ){
        $order = [
            //minimum requirements
            "Source" => $source,
            "SubSource" => $subSource,
            "ReferenceNumber" => $referenceNumber,
            "ExternalReference" => $externalReference,
            "ReceivedDate" => date('c', strtotime($receivedDate)),
            "DispatchBy" => date('c', strtotime($dispatchBy)),

            //Basic info
            "Currency" => $currency, //USD EUR
            'ChannelBuyerName' => $channelBuyerName,
            'MatchPostalServiceTag' => $matchPostalServiceTag,
            'DeliveryAddress' => [
                'EmailAddress' => $deliveryEmailAddress,
                'Address1' => $deliveryAddress1,
                'Address2' => $deliveryAddress2,
                'Address3' => $deliveryAddress3,
                'Town' =>  $deliveryTown,
                'Region' =>  $deliveryRegion,
                'PostCode' =>  $deliveryPostCode,
                'Country' =>  $deliveryCountryName,
                'FullName' =>  $deliveryFullName,
                'Company' =>  $deliveryCompanyName,
                'PhoneNumber' =>  $deliveryPhoneNumber,
                'MatchCountryCode' =>  $deliveryIso3CountryCode
            ],
            'BillingAddress' => [
                'Address1' => $billingAddress1,
                'Address2' => $billingAddress2,
                'Address3' => $billingAddress3,
                'Town' =>  $billingTown,
                'Region' =>  $billingRegion,
                'PostCode' =>  $billingPostCode,
                'Country' =>  $billingCountryName,
                'FullName' =>  $billingFullName,
                'Company' =>  $billingCompanyName,
                'PhoneNumber' =>  $billingPhoneNumber,
                'MatchCountryCode' =>  $billingIso3CountryCode
            ],

            // items
            'AutomaticallyLinkBySKU' => true, //if channel mapping does not work, link by sku
            'MappingSource' => $mappingSource, //use mapping from another channel for SKU's
            'OrderItems'=> $orderItems->getOrderItems(),
            'PostalServiceCost' => $shippingCost, //cost inclusive of cost and after discount
            'PostalServiceTaxRate' => $shippingTaxRate,

            //state
            'OrderState' => $orderState,
            'PaymentStatus' => $paymentStatus,
            'PaidOn' => date('c', strtotime($paidDate))

        ];


        return $this->post('Orders/CreateOrders',[
            'Location' => $locationName,
            'Orders' => json_encode([$order])
        ]);
    }
}