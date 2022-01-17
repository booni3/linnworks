<?php


namespace Booni3\Linnworks\Api;


class Inventory extends ApiClient
{

    public function GetCountries()
    {
        return $this->get('Inventory/GetCountries');
    }

    public function GetChannels()
    {
        return $this->get('Inventory/GetChannels');
    }

}