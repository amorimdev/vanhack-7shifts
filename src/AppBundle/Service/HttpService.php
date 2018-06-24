<?php

namespace AppBundle\Service;

use AppBundle\Contract\HttpInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpService
 * @package AppBundle\Service
 */
class HttpService implements HttpInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * HttpService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri
     * @return ResponseInterface
     */
    public function get(string $uri): ResponseInterface
    {
        return $this->client->get($uri);
    }
}
