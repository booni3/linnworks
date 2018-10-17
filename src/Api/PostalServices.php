<?php

namespace Booni3\Linnworks\Api;

class PostalServices extends Api
{
    public function getPostalServices()
    {
        return $this->_get('PostalServices/GetPostalServices');
    }







}