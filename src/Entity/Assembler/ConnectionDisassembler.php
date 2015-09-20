<?php

namespace OpenConext\JanusClient\Entity\Assembler;

use OpenConext\JanusClient\Dto\ConnectionDto;
use OpenConext\JanusClient\Dto\ConnectionReferenceDto;
use OpenConext\JanusClient\Entity\Connection;
use OpenConext\JanusClient\Entity\ConnectionReference;
use OpenConext\JanusClient\NewConnectionRevision;

final class ConnectionDisassembler
{
    /**
     * @param Connection $newRevision
     * @return ConnectionDto
     */
    public function disassemble(NewConnectionRevision $newRevision)
    {
        $connection = $newRevision->getConnection();
        $dto = new ConnectionDto();
        $dto->revisionNote = $newRevision->getRevisionNote();
        // The API will ignore this, but we send it anyway for easier debugging.
        $dto->revisionNr = $connection->getRevisionNr();

        $dto->isActive = $connection->isActive();
        $dto->name = $connection->getName();
        $dto->id = $connection->getId();
        $dto->state = $connection->getState();
        $dto->type = $connection->getType();
        $dto->metadataUrl = $connection->getMetadataUrl();
        $dto->manipulationCode = $connection->getManipulationCode();
        $dto->metadata = $this->expand($connection->getAllMetadata());
        $dto->arpAttributes = $connection->getArpAttributes()->toArray();
        $dto->disableConsentConnections = $this->disassembleReferences($connection->getConsentDisabledConnections());
        $dto->allowedConnections        = $this->disassembleReferences($connection->getAccess()->getAllowedConnections());
        $dto->blockedConnections        = $this->disassembleReferences($connection->getAccess()->getBlockedConnections());
        $dto->allowAllEntities = $connection->getAccess()->isAllowAll();
        $dto->createdAtDate = $connection->getCreatedDate()->format('c');
        $dto->updatedAtDate = $connection->getUpdatedAtDate()->format('c');

        return $dto;
    }

    /**
     * @param array $flatMetadata
     * @return array
     */
    private function expand(array $flatMetadata)
    {
        $expanded = array();
        foreach ($flatMetadata as $key => $value) {
            $parts = explode(':', $key);
            $lastKey = array_pop($parts);

            $pointer = &$expanded;
            foreach ($parts as $part) {
                if (!isset($pointer[$part])) {
                    $pointer[$part] = array();
                }
                $pointer = &$pointer[$part];
            }

            $pointer[$lastKey] = $value;
        }
        return $expanded;
    }

    /**
     * @param ConnectionReference[] $connectionReferences
     * @return array
     */
    private function disassembleReferences(array $connectionReferences)
    {
        $dtos = array();

        foreach ($connectionReferences as $connectionReference) {
            $dto = new ConnectionReferenceDto();
            $dto->id = $connectionReference->getId();
            $dto->name = $connectionReference->getName();
            $dtos[] = $dto;
        }

        return $dtos;
    }
}
