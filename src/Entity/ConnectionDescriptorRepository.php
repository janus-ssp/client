<?php

namespace OpenConext\JanusClient\Entity;

use Guzzle\Http\Client;
use OpenConext\JanusClient\Dto\ConnectionDescriptorDto;
use OpenConext\JanusClient\Dto\ConnectionDto;
use OpenConext\JanusClient\Entity\Assembler\ConnectionDescriptorAssembler;
use OpenConext\JanusClient\ResponseStatusCodeValidator;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ConnectionDescriptorRepository
 * @package OpenConext\JanusClient\Entity
 */
final class ConnectionDescriptorRepository
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConnectionDescriptorAssembler
     */
    private $assembler;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ResponseStatusCodeValidator
     */
    private $statusCodeValidator;

    /**
     * ConnectionDescriptorRepository constructor.
     * @param Client $client
     * @param LoggerInterface $logger
     * @param ConnectionDescriptorAssembler $assembler
     * @param Serializer $serializer
     * @param ResponseStatusCodeValidator $statusCodeValidator
     */
    public function __construct(
        Client $client,
        LoggerInterface $logger,
        ConnectionDescriptorAssembler $assembler,
        Serializer $serializer,
        ResponseStatusCodeValidator $statusCodeValidator
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->assembler = $assembler;
        $this->serializer = $serializer;
        $this->statusCodeValidator = $statusCodeValidator;
    }

    /**
     * @param $name
     * @return ConnectionDescriptor|null
     */
    public function findByName($name)
    {
        if (empty($name)) {
            throw new RuntimeException("findByName with empty name forbidden");
        }
        $url = $this->getConnectionsUrl($name);
        $request = $this->client->get(
            $this->client->getBaseUrl() . $url
        );

        $response = $request->send();

        $this->statusCodeValidator->validate(200, $url, $request, $response);

        $connections = $this->mapJsonToConnectionDescriptors($response->getBody(true));

        if (count($connections) > 1) {
            throw new RuntimeException('Multiple connections found');
        }

        if (count($connections) === 0) {
            return null;
        }

        return $connections[0];
    }

    /**
     * @param $name
     * @return ConnectionDescriptor
     */
    public function fetchByName($name)
    {
        $connection = $this->findByName($name);

        if (!$connection) {
            throw new RuntimeException('No connection found');
        }

        return $connection;
    }

    /**
     * @return Connection[]
     */
    public function findAll()
    {
        $url = $this->getConnectionsUrl();
        $request = $this->client->get(
            $this->client->getBaseUrl() . $url
        );

        $response = $request->send();

        $this->statusCodeValidator->validate(200, $url, $request, $response);

        return $this->mapJsonToConnectionDescriptors($response->getBody(true));
    }

    /**
     * @return string
     */
    private function getConnectionsUrl($name = '')
    {
        $url = '/connections';

        if (!empty($name)) {
            $url .= '?name=' . urlencode($name);
        }

        return $url;
    }

    /**
     * @param $json
     * @return array
     */
    private function mapJsonToConnectionDescriptors($json)
    {
        $decoded_connections = json_decode($json, true);

        if (!$decoded_connections) {
            $this->logger->error('Mapping received invalid JSON', array('json' => $json));
            throw new \RuntimeException('Mapping received invalid JSON');
        }

        if (!is_array($decoded_connections['connections'])) {
            $this->logger->error('JSON does not contain connection', array('json' => $json));
            throw new RuntimeException('Connections is not an array!');
        }

        $connections = array();
        foreach ($decoded_connections['connections'] as $decoded_connection) {
            /* @var ConnectionDto $dto */
            $dto = $this->serializer->deserialize(
                json_encode($decoded_connection),
                ConnectionDescriptorDto::CLASS_NAME,
                'json'
            );

            $connections[] = $this->assembler->assemble($dto);
        }
        return $connections;
    }
}
