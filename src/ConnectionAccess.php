<?php

namespace OpenConext\JanusClient;

use OpenConext\JanusClient\Entity\ConnectionReference;

final class ConnectionAccess {
  /**
   * @var bool
   */
  private $allowAll;

  /**
   * @var ConnectionReference[]
   */
  private $allowedConnections;

  /**
   * @var ConnectionReference[]
   */
  private $blockedConnections;

  /**
   * ConnectionAccess constructor.
   * @param bool $allowAll
   * @param ConnectionReference[] $allowedConnections
   * @param ConnectionReference[] $blockedConnections
   */
  public function __construct(
    $allowAll = FALSE,
    array $allowedConnections = array(),
    array $blockedConnections = array()
  ) {
    $this->allowAll = $allowAll;
    $this->allowedConnections = $allowedConnections;
    $this->blockedConnections = $blockedConnections;
  }

  public function allowAll() {
    $this->allowAll = true;

    $this->allowedConnections = array();
    $this->blockedConnections = array();
  }

  public function denyAll() {
    $this->allowAll = false;

    $this->allowedConnections = array();
    $this->blockedConnections = array();
  }

  public function allow(ConnectionReference $connection) {
    $this->allowAll = false;

    $this->allowedConnections[$connection->getId()] = $connection;
  }

  public function block(ConnectionReference $connection) {
    $this->allowAll = false;

    $this->blockedConnections[$connection->getId()] = $connection;
  }

  /**
   * @return boolean
   */
  public function isAllowAll()
  {
    return $this->allowAll;
  }

  /**
   * @return Entity\ConnectionReference[]
   */
  public function getAllowedConnections()
  {
    return $this->allowedConnections;
  }

  /**
   * @return Entity\ConnectionReference[]
   */
  public function getBlockedConnections()
  {
    return $this->blockedConnections;
  }
}
