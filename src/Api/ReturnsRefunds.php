<?php


namespace Booni3\Linnworks\Api;


class ReturnsRefunds extends Api
{
    public function getWarehouseLocations()
    {
        return $this->_get('ReturnsRefunds/GetWarehouseLocations');
    }
}