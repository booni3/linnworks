<?php

namespace Booni3\Linnworks\Api;

class PostalServices extends ApiClient
{
    public function getPostalServices()
    {
        return $this->get('PostalServices/GetPostalServices');
    }







}