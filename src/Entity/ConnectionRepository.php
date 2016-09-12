<?php

namespace OpenConext\JanusClient\Entity;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use OpenConext\JanusClient\Dto\ConnectionDto;
use OpenConext\JanusClient\Entity\Assembler\ConnectionAssembler;
use OpenConext\JanusClient\Entity\Assembler\ConnectionDisassembler;
use OpenConext\JanusClient\NewConnectionRevision;
use OpenConext\JanusClient\ResponseStatusCodeValidator;
use RuntimeException;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ConnectionRepository
 * @package OpenConext\JanusClient\Entity
 */
final class ConnectionRepository
{
    private $HEADERS = array(
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    );

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ConnectionAssembler
     */
    private $assembler;

    /**
     * @var ConnectionDisassembler
     */
    private $disassembler;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ResponseStatusCodeValidator
     */
    private $statusCodeValidator;

    /**
     * ConnectionRepository constructor.
     * @param Client $client
     * @param ConnectionAssembler $assembler
     * @param ConnectionDisassembler $disassembler
     * @param Serializer $serializer
     * @param ResponseStatusCodeValidator $statusCodeValidator
     */
    public function __construct(
        Client $client,
        ConnectionAssembler $assembler,
        ConnectionDisassembler $disassembler,
        Serializer $serializer,
        ResponseStatusCodeValidator $statusCodeValidator
    ) {
        $this->client = $client;
        $this->assembler = $assembler;
        $this->disassembler = $disassembler;
        $this->serializer = $serializer;
        $this->statusCodeValidator = $statusCodeValidator;
    }

    /**
     * @param $id
     * @return Connection
     */
    public function findById($id)
    {
        $url = $this->getConnectionUrl($id);
        $request = $this->client->get(
            $this->client->getBaseUrl() . $url,
            $this->HEADERS
        );

        try {
            $response = $request->send();
        } catch (BadResponseException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                return null;
            }
            throw $e;
        }
        if ($response->getStatusCode() === 404) {
            return null;
        }

        $this->statusCodeValidator->validate(200, $url, $request, $response);

        return $this->mapJsonToConnection($response->getBody(true));
    }


    /**
     * @param $id
     * @return Connection
     */
    public function fetchById($id)
    {
        $connection = $this->findById($id);

        if (!$connection) {
            throw new RuntimeException("Missing expected connection for id $id");
        }

        return $connection;
    }

    /**
     * @param NewConnectionRevision $newRevision
     * @return Connection
     */
    public function insert(NewConnectionRevision $newRevision)
    {
        $url = '/connections';

        $request = $this->client->post(
            $this->client->getBaseUrl() . $url,
            $this->HEADERS,
            $this->mapNewRevisionToJson($newRevision)
        );

        $response = $request->send();

        $this->statusCodeValidator->validate(201, $url, $request, $response);

        return $this->mapJsonToConnection($response->getBody(true));
    }

    /**
     * @param NewConnectionRevision $newRevision
     * @return Connection
     */
    public function update(NewConnectionRevision $newRevision)
    {
        $url = $this->getConnectionUrl(
            $newRevision->getConnection()->getId()
        );
        $request = $this->client->put(
            $this->client->getBaseUrl() . $url,
            $this->HEADERS,
            $this->serializer->serialize(
                $this->disassembler->disassemble($newRevision),
                'json'
            )
        );

        $response = $request->send();

        $this->statusCodeValidator->validate(201, $url, $request, $response);

        return $this->mapJsonToConnection($response->getBody(true));
    }

    /**
     * @param string $id
     */
    public function delete($id)
    {
        $url = $this->getConnectionUrl($id);

        $request = $this->client->delete(
            $this->client->getBaseUrl() . $url,
            $this->HEADERS
        );

        $response = $request->send();

        $this->statusCodeValidator->validate(200, $url, $request, $response);
    }

    /**
     * @param $json
     * @return Connection
     */
    private function mapJsonToConnection($json)
    {
        /* @var ConnectionDto $dto */
        $dto = $this->serializer->deserialize(
            $json,
            ConnectionDto::CLASS_NAME,
            'json'
        );

        $connection = $this->assembler->assemble($dto);
        return $connection;
    }

    /**
     * @param NewConnectionRevision $newRevision
     * @return string
     */
    private function mapNewRevisionToJson(NewConnectionRevision $newRevision)
    {
        $dto = $this->disassembler->disassemble($newRevision);
        return $this->serializer->serialize($dto, 'json');
    }

    /**
     * @param string $id
     * @return string
     */
    private function getConnectionUrl($id)
    {
        return '/connections/' . $id;
    }
}
