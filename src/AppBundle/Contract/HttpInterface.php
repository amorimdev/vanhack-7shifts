<?php

namespace AppBundle\Contract;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface HttpInterface
 * @package AppBundle\Contract
 */
interface HttpInterface
{
    /**
     * @param string $uri
     * @return ResponseInterface
     */
    public function get(string $uri): ResponseInterface;
}
