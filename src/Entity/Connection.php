<?php

namespace OpenConext\JanusClient\Entity;

use DateTimeImmutable;
use OpenConext\JanusClient\ConnectionAccess;
use OpenConext\JanusClient\ConnectionRevision;

final class Connection extends ConnectionDescriptor
{
    /**
     * @var array
     */
    private $metadata;

    /**
     * @var string
     */
    private $metadataUrl;

    /**
     * @var string
     */
    private $manipulationCode;

    /**
     * @var ConnectionAccess
     */
    private $access;

    /**
     * @var ConnectionReference[]
     */
    private $consentDisabledConnections;

    /**
     * @var ConnectionRevision
     */
    private $revision;
    /**
     * @var DateTimeImmutable
     */
    private $createdDate;

    /**
     * @var DateTimeImmutable
     */
    private $updatedAtDate;

    /**
     * Connection constructor.
     * @param string $name
     * @param string $type
     * @param string $state
     * @param array $metadata
     * @param string $metadataUrl
     * @param string $manipulationCode
     * @param DateTimeImmutable $createdDate
     * @param DateTimeImmutable $updatedAtDate
     * @param bool $isActive
     * @param string $id
     * @param ConnectionRevision $revision
     * @param ConnectionAccess $access
     */
    public function __construct(
        $name,
        $type,
        $state,
        array $metadata,
        $metadataUrl,
        $manipulationCode = '',
        ConnectionAccess $access = NULL,
        $consentDisabledConnections = array(),
        $isActive = TRUE,
        $id = NULL,
        ConnectionRevision $revision = NULL,
        DateTimeImmutable $createdDate = NULL,
        DateTimeImmutable $updatedAtDate = NULL
    )
    {
        parent::__construct(
            $id,
            $name,
            $revision ? $revision->getRevisionNr() : NULL,
            $state,
            $type,
            $isActive
        );
        $this->metadata = $metadata;

        $this->metadataUrl = $metadataUrl;

        $this->manipulationCode = $manipulationCode;

        if (!$access) {
            $access = new ConnectionAccess();
        }
        $this->access = $access;

        $this->consentDisabledConnections = $consentDisabledConnections;

        $this->revision = $revision;

        if (!$createdDate) {
            $createdDate = new DateTimeImmutable();
        }
        $this->createdDate = $createdDate;

        if (!$updatedAtDate) {
            $updatedAtDate = new DateTimeImmutable();
        }
        $this->updatedAtDate = $updatedAtDate;
    }

    public function rename($newName)
    {
        $this->name = $newName;
        $this->updatedAtDate = new DateTimeImmutable();

        return $this;
    }

    public function getAllMetadata() {
        return $this->metadata;
    }

    public function getMetadata($key)
    {
        return $this->metadata[$key];
    }

    public function setMetadata($key, $value)
    {
        if (isset($this->metadata[$key]) && $this->metadata[$key] === $value) {
            return $this;
        }

        $this->metadata[$key] = $value;
        $this->updatedAtDate = new DateTimeImmutable();

        return $this;
    }

    public function disableConsentFor(ConnectionReference $connection)
    {
        $this->updatedAtDate = new DateTimeImmutable();
        $this->consentDisabledConnections[] = $connection;

        return $this;
    }

    public function activate()
    {
        $this->isActive = true;
        $this->updatedAtDate = new DateTimeImmutable();

        return $this;
    }

    public function deactivate()
    {
        $this->isActive = false;
        $this->updatedAtDate = new DateTimeImmutable();

        return $this;
    }

    public function manipulate($code)
    {
        $this->manipulationCode = $code;
        $this->updatedAtDate = new DateTimeImmutable();

        return $this;
    }

    public function acceptForProduction()
    {
        $this->state = static::WORKFLOW_PROD;
        $this->updatedAtDate = new DateTimeImmutable();

        return $this;
    }

    /**
     * @return string
     */
    public function getMetadataUrl()
    {
        return $this->metadataUrl;
    }

    /**
     * @return string
     */
    public function getManipulationCode()
    {
        return $this->manipulationCode;
    }

    /**
     * @return ConnectionAccess
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @return ConnectionReference[]
     */
    public function getConsentDisabledConnections()
    {
        return $this->consentDisabledConnections;
    }

    /**
     * @return ConnectionRevision
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAtDate()
    {
        return $this->updatedAtDate;
    }
}
