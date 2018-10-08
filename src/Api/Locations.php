<?php


namespace Booni3\Linnworks\Api;


class Locations extends Api
{

    public function GetCountries()
    {
        return $this->_get('Inventory/GetCountries');
    }

}