<?php

namespace Booni3\Linnworks\Api;

class Auth extends ApiClient
{

    public function __construct($applicationId, $applicationSecret, $token, $bearer = null, $server = null)
    {
        parent::__construct($applicationId, $applicationSecret, $token, $bearer, $server);
    }

    public function AuthorizeByApplication()
    {
        return $this->get('Auth/AuthorizeByApplication', [
            "applicationId" => $this->applicationId,
            "applicationSecret" => $this->applicationSecret,
            "token" => $this->token
        ]);
    }
}