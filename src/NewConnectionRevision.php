<?php

namespace OpenConext\JanusClient;

use OpenConext\JanusClient\Entity\Connection;

/**
 * Class NewConnectionRevision
 * @package OpenConext\JanusClient
 */
final class NewConnectionRevision
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $revisionNote;

    /**
     * NewConnectionRevision constructor.
     * @param Connection $connection
     * @param string $revisionNote
     */
    public function __construct(Connection $connection, $revisionNote = '')
    {
        $this->connection = $connection;
        $this->revisionNote = $revisionNote;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return mixed
     */
    public function getRevisionNote()
    {
        return $this->revisionNote;
    }
}
