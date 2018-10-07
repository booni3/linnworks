<?php


namespace Booni3\Linnworks\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

class Api
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

    /**
     * {@inheritdoc}
     */
    public function _get($url = null, array $parameters = [])
    {
        return $this->execute('get', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _post($url = null, array $parameters = [])
    {
        return $this->execute('post', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($httpMethod, $url, array $parameters = [])
    {
        try {
            $response = $this->getClient()->{$httpMethod}($url, [
                'form_params' => $parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => $this->bearer
                ]
            ]);
            return json_decode((string)$response->getBody(), true);
        } catch (ClientException $e) {
            throw $e;
        }
    }

    /**
     * Returns an Http client instance.
     *
     * @return Client
     */
    protected function getClient()
    {
        $server = $this->server ?? 'https://api.linnworks.net';
        return new Client([
            'base_uri' => $server . '/api/',
            'handler' => $this->createHandler()
        ]);
    }

    /**
     * Create the client handler.
     *
     * @return HandlerStack
     */
    protected function createHandler()
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
        $handler_stack->push(Middleware::mapRequest(function (RequestInterface $r) {
            //
            return $r;
        }));
//        $handler_stack->push($this->addheader('Authorization', $this->bearer));
        return $handler_stack;
    }

    /**
     * Add header to request
     *
     * @param $header
     * @param $value
     * @return \Closure
     */
    protected function addheader($header, $value)
    {
        return function (callable $handler) use ($header, $value) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler, $header, $value) {
                $request = $request->withHeader($header, $value);
                return $handler($request, $options);
            };
        };
    }
}