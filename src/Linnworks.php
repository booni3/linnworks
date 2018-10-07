<?php

namespace Booni3\Linnworks;

class Linnworks
{
    private $applicationId;
    private $applicationSecret;
    private $token;
    protected $bearer;
    protected $server;


    /**
     * Linnworks constructor.
     *
     * @param $applicationId
     * @param $applicationSecret
     * @param $token
     * @throws \ReflectionException
     */
    public function __construct($applicationId, $applicationSecret, $token)
    {
        $this->applicationId = $applicationId;
        $this->applicationSecret = $applicationSecret;
        $this->token = $token;

        if(!$this->bearer) $this->refreshToken();
    }

    /**
     * Create instance of Client
     *
     * @param $applicationId
     * @param $applicationSecret
     * @param $token
     * @return \Booni3\Linnworks\Linnworks;
     * @throws \ReflectionException
     */
    public static function make($applicationId, $applicationSecret, $token)
    {
        return new static ($applicationId, $applicationSecret, $token);
    }

    /**
     * Refresh the token using AuthorizeByApplication
     *
     * @throws \ReflectionException
     */
    public function refreshToken()
    {
        $res = $this->Auth()->AuthorizeByApplication();
        $this->bearer = $res['Token'];
        $this->server = $res['Server'];
    }

    /**
     * Create instance of API based off method called in
     *
     * @param $method
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getApiInstance($method)
    {
        $class = "\\Booni3\\Linnworks\\Api\\".ucwords($method);
        if (class_exists($class) && ! (new \ReflectionClass($class))->isAbstract()) {
            return new $class($this->applicationId, $this->applicationSecret, $this->token, $this->bearer, $this->server);
        }
        throw new \BadMethodCallException("Undefined method [{$method}] called.");
    }


    /**
     * @return \Booni3\Linnworks\Api\Auth
     * @throws \ReflectionException
     */
    public function Auth()
    {
        return $this->getApiInstance('auth');
    }

    /**
     * @return \Booni3\Linnworks\Api\Orders
     * @throws \ReflectionException
     */
    public function Orders()
    {
        return $this->getApiInstance('orders');
    }

    /**
     * @param $method
     * @param array $parameters
     * @throws \Exception
     * @return mixed
     */
    public function __call($method, array $parameters)
    {
        return $this->getApiInstance($method);
    }

}