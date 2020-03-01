<?php

namespace TehekOne\Firebase\Client;

use TehekOne\Firebase\Client;

/**
 * Interface ClientAwareInterface
 *
 * @package TehekOne\Firebase\Client
 */
interface ClientAwareInterface
{
    /**
     * @param Client $client
     *
     * @return self
     */
    public function setClient(Client $client);
}
