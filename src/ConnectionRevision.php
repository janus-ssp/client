<?php

namespace OpenConext\JanusClient;

/**
 * Class ConnectionRevision
 * @package OpenConext\JanusClient
 */
final class ConnectionRevision
{
    /**
     * @var string
     */
    private $revisionNote;

    /**
     * @var int
     */
    private $revisionNr;

    /**
     * @var int
     */
    private $parentRevisionNr;

    /**
     * @var string
     */
    private $updatedByUserName;

    /**
     * @var string
     */
    private $updatedFromIp;

    /**
     * ConnectionRevision constructor.
     * @param string $revisionNote
     * @param int $revisionNr
     * @param int $parentRevisionNr
     * @param string $updatedByUserName
     * @param string $updatedFromIp
     */
    public function __construct(
        $revisionNote,
        $revisionNr,
        $parentRevisionNr,
        $updatedByUserName,
        $updatedFromIp
    ) {
        $this->revisionNote = $revisionNote;
        $this->revisionNr = $revisionNr;
        $this->parentRevisionNr = $parentRevisionNr;
        $this->updatedByUserName = $updatedByUserName;
        $this->updatedFromIp = $updatedFromIp;
    }

    /**
     * @return string
     */
    public function getRevisionNote()
    {
        return $this->revisionNote;
    }

    /**
     * @return int
     */
    public function getRevisionNr()
    {
        return $this->revisionNr;
    }

    /**
     * @return int
     */
    public function getParentRevisionNr()
    {
        return $this->parentRevisionNr;
    }

    /**
     * @return string
     */
    public function getUpdatedByUserName()
    {
        return $this->updatedByUserName;
    }

    /**
     * @return string
     */
    public function getUpdatedFromIp()
    {
        return $this->updatedFromIp;
    }
}
