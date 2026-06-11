<?php

declare(strict_types=1);

namespace Booni3\Linnworks;

use Booni3\Linnworks\Api\Auth;
use Booni3\Linnworks\Api\Inventory;
use Booni3\Linnworks\Api\OrderItems;
use Booni3\Linnworks\Api\Orders;
use Booni3\Linnworks\Api\PostalServices;
use Booni3\Linnworks\Api\ReturnsRefunds;
use Booni3\Linnworks\Api\Stock;
use Booni3\Linnworks\Exceptions\LinnworksAuthenticationException;
use GuzzleHttp\Client as GuzzleClient;

class Linnworks
{
    public const BASE_URI = 'https://api.linnworks.net';

    protected GuzzleClient $client;

    protected array $config;

    protected string $bearer = '';

    protected string $server = '';

    public function __construct(
        array $config,
        ?GuzzleClient $client = null
    ) {
        $this->config = $config;
        $this->client = $client ?? $this->makeClient();

        if (empty($this->bearer)) {
            $this->refreshToken();
        }
    }

    public static function make(array $config, ?GuzzleClient $client = null): self
    {
        return new static($config, $client);
    }

    private function makeClient(): GuzzleClient
    {
        return new GuzzleClient([
            'timeout' => $this->config['timeout'] ?? 15
        ]);
    }

    /**
     * @throws LinnworksAuthenticationException
     */
    private function refreshToken(): void
    {
        $parameters = [
            "ApplicationId" => $this->config['applicationId'] ?? '',
            "ApplicationSecret" => $this->config['applicationSecret'] ?? '',
            "Token" => $this->config['token'] ?? ''
        ];

        $response = (new Auth($this->client, self::BASE_URI.'/api/', null))
            ->AuthorizeByApplication($parameters);

        if (empty($response['Token'])) {
            throw new LinnworksAuthenticationException($response['message'] ?? 'Authentication failed');
        }

        $this->bearer = $response['Token'];
        $this->server = $response['Server'] . '/api/';
    }

    public function orders(): Orders
    {
        return new Orders($this->client, $this->server, $this->bearer);
    }

    public function inventory(): Inventory
    {
        return new Inventory($this->client, $this->server, $this->bearer);
    }

    public function postalServices(): PostalServices
    {
        return new PostalServices($this->client, $this->server, $this->bearer);
    }

    public function returnsRefunds(): ReturnsRefunds
    {
        return new ReturnsRefunds($this->client, $this->server, $this->bearer);
    }

    public function stock(): Stock
    {
        return new Stock($this->client, $this->server, $this->bearer);
    }

    public function orderItems(): OrderItems
    {
        return new OrderItems();
    }
}
