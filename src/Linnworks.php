<?php

namespace Booni3\Linnworks;

class Linnworks
{
    private $applicationId;
    private $applicationSecret;
    private $token;
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
     * Create instance of Client
     *
     * @return Linnworks
     */
    public function make()
    {
        if(!$this->bearer) $this->refreshToken();
        return new static ($this->applicationId, $this->applicationSecret, $this->token, $this->bearer, $this->server);
    }

    /**
     * Refresh the token using AuthorizeByApplication
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