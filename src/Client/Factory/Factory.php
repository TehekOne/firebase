<?php

namespace TehekOne\Firebase\Client\Factory;

use RuntimeException;
use TehekOne\Firebase\Client;

/**
 * Class Factory
 *
 * @package TehekOne\Firebase\Client\Factory
 */
class Factory
{
    /**
     * Map of api namespaces to classes.
     *
     * @var array
     */
    protected $map = [];

    /**
     * Map of instances.
     *
     * @var array
     */
    protected $cache = [];

    /**
     * @var Client
     */
    private $client;

    /**
     * Factory constructor.
     *
     * @param array $map
     * @param Client $client
     */
    public function __construct(array $map, Client $client)
    {
        $this->map = $map;
        $this->client = $client;
    }

    /**
     * @param $api
     *
     * @return mixed
     */
    public function __get($api)
    {
        if (isset($this->cache[$api])) {
            return $this->cache[$api];
        }

        if (!$this->$api) {
            throw new RuntimeException('No map defined for '.$api);
        }

        $class = $this->map[$api];

        $instance = new $class();

        if ($instance instanceof Client\ClientAwareInterface) {
            $instance->setClient($this->client);
        }

        $this->cache[$api] = $instance;

        return $class;
    }

    /**
     * @param $api
     *
     * @return bool
     */
    public function __isset($api)
    {
        return isset($this->map[$api]);
    }
}
