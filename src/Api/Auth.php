<?php

namespace Booni3\Linnworks\Api;

class Auth extends Api
{
    public function AuthorizeByApplication()
    {
        return $this->_get('Auth/AuthorizeByApplication', [
            "applicationId" => $this->applicationId,
            "applicationSecret" => $this->applicationSecret,
            "token" => $this->token
        ]);
    }
}