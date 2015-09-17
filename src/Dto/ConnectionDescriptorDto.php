<?php

namespace OpenConext\JanusClient\Dto;

class ConnectionDescriptorDto extends ConnectionReferenceDto
{
    const CLASS_NAME = 'OpenConext\\JanusClient\\Dto\\ConnectionDescriptorDto';

    /**
     * @var string
     */
    public $revisionNr;

    /**
     * @var string
     */
    public $state;

    /**
     * @var string
     */
    public $type;

    /**
     * @var bool
     */
    public $isActive;
}
