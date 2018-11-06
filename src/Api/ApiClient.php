<?php


namespace Booni3\Linnworks\Api;

use Booni3\Linnworks\Linnworks;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

class ApiClient
{
    protected $applicationId;
    protected $applicationSecret;
    protected $token;
    protected $bearer;
    protected $server;

    public function __construct($applicationId, $applicationSecret, $token, $bearer = null, $server = null)
    {
        $this->applicationId = $applicationId;
        $this->applicationSecret = $applicationSecret;
        $this->token = $token;
        $this->bearer = $bearer;
        $this->server = $server;
    }

    public function get($url = null, array $parameters = []) : array
    {
        try {
            $response = $this->getClient()->get($url, [
                'form_params' => $parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => $this->bearer
                ]
            ]);
            return json_decode((string)$response->getBody(), true);
        } catch (ClientException $e) {
            $responseBodyAsString = $e->getResponse()->getBody()->getContents();
            throw new \Exception($responseBodyAsString, $e->getResponse()->getStatusCode());
        }
    }

    public function post($url = null, array $parameters = []) : array
    {
        try {
            $response = $this->getClient()->post($url, [
                'form_params' => $parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'Authorization' => $this->bearer ?? ''
                ]
            ]);
            return json_decode((string)$response->getBody(), true);
        } catch (ClientException $e) {
            $responseBodyAsString = $e->getResponse()->getBody()->getContents();
            throw new \Exception($responseBodyAsString, $e->getResponse()->getStatusCode());
        }
    }

    public function getClient() : Client
    {
        $server = $this->server ?? Linnworks::BASE_URI;
        return new Client([
            'base_uri' => $server . '/api/',
            'handler' => $this->createHandler()
        ]);
    }

    public function createHandler() : HandlerStack
    {
        $handler_stack = HandlerStack::create();
        $handler_stack->push(Middleware::retry(
            function ($retry, $request, $value, $reason) {
                if ($value !== null) return false; // If we have a value already, we should be able to proceed quickly.
                return $retry < 10; // reject after 10 tries
            },
            function ($retries, $response) {
                return $retries * 200; //0.2, 0.4, 0.6 seconds etc..
            }
        ));
        return $handler_stack;
    }

}