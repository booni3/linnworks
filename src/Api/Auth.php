<?php

namespace Booni3\Linnworks\Api;

class Auth extends ApiClient
{

    public function AuthorizeByApplication()
    {
        return $this->get('Auth/AuthorizeByApplication', [
            "applicationId" => $this->applicationId,
            "applicationSecret" => $this->applicationSecret,
            "token" => $this->token
        ]);
    }
}