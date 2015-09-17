<?php

namespace OpenConext\JanusClient;

use OpenConext\JanusClient\Entity\Connection;

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
     * @param $revisionNote
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
