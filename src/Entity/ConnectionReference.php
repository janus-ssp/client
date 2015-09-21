<?php

namespace OpenConext\JanusClient\Entity;

/**
 * Class ConnectionReference
 * @package OpenConext\JanusClient\Entity
 */
class ConnectionReference {
  /**
   * @var int
   */
  protected $id;

  /**
   * @var string
   */
  protected $name;

  /**
   * ConnectionReference constructor.
   * @param string $id
   * @param string $name
   */
  public function __construct($id, $name) {
    $this->id = (int) $id;
    $this->name = $name;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }
}
