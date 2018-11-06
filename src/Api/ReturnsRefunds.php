<?php


namespace Booni3\Linnworks\Api;


class ReturnsRefunds extends ApiClient
{
    public function __construct($applicationId, $applicationSecret, $token, $bearer = null, $server = null)
    {
        parent::__construct($applicationId, $applicationSecret, $token, $bearer, $server);
    }

    public function getWarehouseLocations()
    {
        return $this->get('ReturnsRefunds/GetWarehouseLocations');
    }
}