<?php

namespace OpenConext\JanusClient;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Class ResponseStatusCodeValidator
 * @package OpenConext\JanusClient
 */
final class ResponseStatusCodeValidator
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ResponseStatusCodeValidator constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $statusCode
     * @param $url
     * @param RequestInterface $request
     * @param Response $response
     */
    public function validate(
        $statusCode,
        $url,
        RequestInterface $request,
        Response $response
    ) {
        if ($response->getStatusCode() === $statusCode) {
            return;
        }

        $message = $url . ' gives a non-200 status code response.';
        $this->logger->warning(
            $message,
            array(
                'request' => (string) $request,
                'response' => (string) $response,
            )
        );
        throw new RuntimeException(
            $message . ' See logs.' . $response->serialize()
        );
    }
}
