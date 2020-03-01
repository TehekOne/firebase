<?php

namespace TehekOne\Firebase;

use Google\Auth\FetchAuthTokenInterface;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\HandlerStack;
use Http\Client\HttpClient;
use PackageVersions\Versions;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use TehekOne\Firebase\Client\Factory\Factory;

/**
 * Class Client
 *
 * @package TehekOne\Firebase
 */
class Client
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * Client constructor.
     *
     * @param FetchAuthTokenInterface $credentials
     * @param HttpClient $client
     */
    public function __construct(FetchAuthTokenInterface $credentials, HttpClient $client = null)
    {
        $this->factory = new Factory([
            \TehekOne\Firebase\Message\Client::class,
        ], $this);

        if ($client === null) {
            $stack = HandlerStack::create();
            $stack->push(new AuthTokenMiddleware($credentials));

            $client = \Http\Adapter\Guzzle6\Client::createWithConfig([
                'handler' => $stack,
                'auth' => 'google_auth',
            ]);
        }

        $this->setClient($client);
    }

    /**
     * @param $name
     * @param $args
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (!$this->factory->$name) {
            throw new RuntimeException('No api namespace found: '.$name);
        }

        $api = $this->factory->$name;

        return empty($args) ? $api : call_user_func_array($api, $args);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->factory->$name) {
            throw new RuntimeException('No api namespace found: '.$name);
        }

        return $this->$name;
    }

    /**
     * @return HttpClient
     */
    public function getClient(): HttpClient
    {
        return $this->client;
    }

    /**
     * @param HttpClient $client
     *
     * @return Client
     */
    public function setClient(HttpClient $client): Client
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param RequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function send(RequestInterface $request)
    {
        $userAgent[] = 'firebase-php/'.static::version();
        $userAgent[] = 'php/'.PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;

        $request->withHeader('User-Agent', implode(' ', $userAgent));

        return $this->client->sendRequest($request);
    }

    /**
     * @return string
     */
    public static function version()
    {
        return Versions::getVersion('tehekone/firebase');
    }
}
