<?php

namespace OpenConext\JanusClient\Dto;

class ConnectionDto extends ConnectionDescriptorDto
{
    const CLASS_NAME = 'OpenConext\\JanusClient\\Dto\\ConnectionDto';

    /**
     * @var string
     */
    public $updatedByUserName;

    /**
     * @var string
     */
    public $updatedFromIp;

    /**
     * @var string
     */
    public $metadataUrl;

    /**
     * @var string
     */
    public $allowAllEntities;

    /**
     * @var string
     */
    public $manipulationCode;

    /**
     * @var string
     */
    public $parentRevisionNr;

    /**
     * @var string
     */
    public $revisionNote;

    /**
     * @var string
     */
    public $createdAtDate;

    /**
     * @var string
     */
    public $updatedAtDate;

    /**
     * @var array<string,array<string>>
     */
    public $arpAttributes;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var ConnectionReferenceDto[]
     */
    public $allowedConnections;

    /**
     * @var ConnectionReferenceDto[]
     */
    public $blockedConnections;

    /**
     * @var ConnectionReferenceDto[]
     */
    public $disableConsentConnections;
}
