<?php

namespace OpenConext\JanusClient\Entity\Assembler;

use DateTimeImmutable;
use OpenConext\JanusClient\ArpAttributes;
use OpenConext\JanusClient\ConnectionAccess;
use OpenConext\JanusClient\ConnectionRevision;
use OpenConext\JanusClient\Dto\ConnectionDto;
use OpenConext\JanusClient\Entity\Connection;
use OpenConext\JanusClient\Entity\ConnectionReference;

/**
 * Class ConnectionAssembler
 * @package OpenConext\JanusClient\Entity\Assembler
 */
final class ConnectionAssembler
{
    /**
     * @param ConnectionDto $dto
     * @return Connection
     */
    public function assemble(ConnectionDto $dto)
    {
        return new Connection(
            $dto->name,
            $dto->type,
            $dto->state,
            $this->flattenMetadata($dto->metadata),
            $dto->metadataUrl,
            $dto->manipulationCode,
            new ConnectionAccess(
                $dto->allowAllEntities,
                $this->makeReferences($dto->allowedConnections),
                $this->makeReferences($dto->blockedConnections)
            ),
            $dto->arpAttributes ? new ArpAttributes($dto->arpAttributes) : null,
            $this->makeReferences($dto->disableConsentConnections),
            $dto->isActive,
            $dto->id,
            new ConnectionRevision(
                $dto->revisionNote,
                $dto->revisionNr,
                $dto->parentRevisionNr,
                $dto->updatedByUserName,
                $dto->updatedFromIp
            ),
            new DateTimeImmutable($dto->createdAtDate),
            new DateTimeImmutable($dto->updatedAtDate)
        );
    }

    /**
     * @param array $metadata
     * @param string $prefix
     * @return array
     */
    private function flattenMetadata(array $metadata, $prefix = '')
    {
        if (!empty($prefix)) {
            $prefix .= ':';
        }

        $newValues = array();
        foreach ($metadata as $key => $value) {
            if (is_array($value)) {
                $newValues = array_merge(
                    $newValues,
                    $this->flattenMetadata($value, $prefix . $key)
                );
            } else {
                $newValues[$prefix . $key] = $value;
            }
        }
        return $newValues;
    }

    /**
     * @param array $connections
     * @return array
     */
    private function makeReferences(array $connections)
    {
        $references = array();
        foreach ($connections as $connection) {
            $references[] = new ConnectionReference(
                $connection['id'],
                $connection['name']
            );
        }
        return $references;
    }
}
