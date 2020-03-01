<?php

namespace TehekOne\Firebase\Client;

use RuntimeException;
use TehekOne\Firebase\Client;

/**
 * Trait ClientAwareTrait
 *
 * @package TehekOne\Firebase\Client
 */
trait ClientAwareTrait
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @return Client
     */
    protected function getClient(): Client
    {
        if (isset($this->client)) {
            return $this->client;
        }

        throw new RuntimeException('TehekOne\Firebase\Client not set');
    }

    /**
     * @param Client $client
     *
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }
}
