<?php

namespace OpenConext\JanusClient\Entity\Assembler;

use OpenConext\JanusClient\Dto\ConnectionDescriptorDto;
use OpenConext\JanusClient\Dto\ConnectionDto;
use OpenConext\JanusClient\Entity\Connection;
use OpenConext\JanusClient\Entity\ConnectionDescriptor;

final class ConnectionDescriptorAssembler
{
    /**
     * @param ConnectionDto $dto
     * @return Connection
     */
    public function assemble(ConnectionDescriptorDto $dto)
    {
        return new ConnectionDescriptor(
            $dto->id,
            $dto->name,
            $dto->revisionNr,
            $dto->state,
            $dto->type,
            $dto->isActive
        );
    }
}
