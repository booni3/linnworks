<?php

namespace Booni3\Linnworks;

use Booni3\Linnworks\Api\Auth;
use Booni3\Linnworks\Api\Locations;
use Booni3\Linnworks\Api\Orders;
use Booni3\Linnworks\Api\PostalServices;
use Booni3\Linnworks\Api\ReturnsRefunds;
use Booni3\Linnworks\Api\Stock;

class Linnworks
{
    private $applicationId;
    private $applicationSecret;
    private $token;
    protected $bearer;
    protected $server;

    const BASE_URI = 'https://api.linnworks.net';

    const UNPAID = 0;
    const PAID = 1;
    const RETURN = 2;
    const PENDING = 3;
    const RESEND = 4;

    public function __construct(array $config)
    {
        $this->applicationId = $config['applicationId'];
        $this->applicationSecret = $config['applicationSecret'];
        $this->token = $config['token'];

        if(! $this->bearer) {
            $this->refreshToken();
        }
    }

    public static function make(array $config): self
    {
        return new static ($config);
    }

    public function refreshToken() : void
    {
        $response = $this->auth()->AuthorizeByApplication();

        if(!isset($response['Token'])){
            throw new \Exception('Could not login.' . $response['message'] ?? '');
        }

        $this->bearer = $response['Token'];
        $this->server = $response['Server'];
    }

    protected function auth(): Auth
    {
        return new Auth($this->applicationId, $this->applicationSecret, $this->token);
    }

    public function orders(): Orders
    {
        return new Orders($this->applicationId, $this->applicationSecret, $this->token, $this->bearer, $this->server);
    }

    public function locations(): Locations
    {
        return new Locations($this->applicationId, $this->applicationSecret, $this->token, $this->bearer, $this->server);
    }

    public function postalServices(): PostalServices
    {
        return new PostalServices($this->applicationId, $this->applicationSecret, $this->token, $this->bearer, $this->server);
    }

    public function returnsRefunds(): ReturnsRefunds
    {
        return new ReturnsRefunds($this->applicationId, $this->applicationSecret, $this->token, $this->bearer, $this->server);
    }

    public function stock(): Stock
    {
        return new Stock($this->applicationId, $this->applicationSecret, $this->token, $this->bearer, $this->server);
    }

}