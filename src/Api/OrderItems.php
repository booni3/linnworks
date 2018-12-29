<?php


namespace Booni3\Linnworks\Api;


class OrderItems
{

    protected $itemNumber = 0;
    protected $orderItems = [];

    public function addOrderItem(string $sku = "",
                                 string $title = "",
                                 int $qty = 0,
                                 float $pricePerUnit = 0,
                                 int $taxRate = 20,
                                 bool $taxCostInclusive = true,
                                 bool $useChannelTax = false,
                                 bool $isService = false,
                                 float $lineDiscount = 0
    ){
        $this->orderItems[] = array (
            'TaxCostInclusive'=> $taxCostInclusive,
            'UseChannelTax'=> $useChannelTax,
            'PricePerUnit'=> $pricePerUnit,
            'Qty'=> $qty,
            'TaxRate'=> $taxRate,
            'LineDiscount'=> $lineDiscount,
            'ItemNumber'=> $this->itemNumber++,
            'ChannelSKU'=> $sku,
            'IsService'=> $isService,
            'ItemTitle'=> $title
        );
    }

    public function getOrderItems()
    {
        return $this->orderItems;
    }
}