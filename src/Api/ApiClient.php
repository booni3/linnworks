<?php

declare(strict_types=1);

namespace Booni3\Linnworks\Api;

use Booni3\Linnworks\Exceptions\LinnworksResponseCouldNotBeParsed;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{
    public function __construct(
        private readonly Client $client,
        private readonly ?string $server = null,
        private readonly ?string $bearer = null
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws LinnworksResponseCouldNotBeParsed
     */
    public function get(?string $url = null, array $parameters = []): array
    {
        return $this->parse(function () use ($url, $parameters): ResponseInterface {
            return $this->client->get($this->server.$url, [
                'form_params' => $parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => $this->bearer ?? '',
                ],
            ]);
        });
    }

    /**
     * @throws GuzzleException
     * @throws LinnworksResponseCouldNotBeParsed
     */
    public function post(?string $url = null, array $parameters = []): array
    {
        return $this->parse(function () use ($url, $parameters): ResponseInterface {
            return $this->client->post($this->server.$url, [
                'form_params' => $parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'Authorization' => $this->bearer ?? '',
                ],
            ]);
        });
    }

    /**
     * @throws GuzzleException
     * @throws LinnworksResponseCouldNotBeParsed
     */
    public function postJson(string $url, array $payload = []): array
    {
        return $this->parse(function () use ($url, $payload): ResponseInterface {
            return $this->client->post($this->server.$url, [
                'json' => $payload,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $this->bearer ?? '',
                ],
            ]);
        });
    }

    /**
     * @throws LinnworksResponseCouldNotBeParsed
     */
    private function parse(callable $callback): array
    {
        $response = $callback();

        $body = (string) $response->getBody();

        if ($body === '') {
            return [];
        }

        try {
            $json = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new LinnworksResponseCouldNotBeParsed($body);
        }

        if (! is_array($json)) {
            throw new LinnworksResponseCouldNotBeParsed($body);
        }

        return $json;
    }
}
