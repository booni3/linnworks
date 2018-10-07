<?php

namespace Booni3\Linnworks\Api;

class Auth extends Api
{
    public function AuthorizeByApplication()
    {
        $res = $this->_get('Auth/AuthorizeByApplication', [
            "applicationId" => $this->applicationId,
            "applicationSecret" => $this->applicationSecret,
            "token" => $this->token
        ]);
        return $res;
    }
}