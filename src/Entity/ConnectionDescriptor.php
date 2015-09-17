<?php

namespace OpenConext\JanusClient\Entity;

class ConnectionDescriptor extends ConnectionReference
{
    const TYPE_SP = 'saml20-sp';
    const TYPE_IDP = 'saml20-idp';

    const WORKFLOW_TEST = 'testaccepted';
    const WORKFLOW_PROD = 'prodaccepted';

    /**
     * @var int
     */
    protected $revisionNr;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $isActive;

    /**
     * ConnectionDescriptor constructor.
     * @param int $revisionNr
     * @param string $state
     * @param string $type
     * @param bool $isActive
     */
    public function __construct(
        $id,
        $name,
        $revisionNr,
        $state,
        $type,
        $isActive
    )
    {
        parent::__construct($id, $name);
        $this->revisionNr = (int) $revisionNr;

        if (!in_array($state, array(static::WORKFLOW_PROD, static::WORKFLOW_TEST))) {
            throw new \RuntimeException('Invalid workflow state: ' . $state);
        }
        $this->state = $state;

        if (!in_array($type, array(static::TYPE_IDP, static::TYPE_SP))) {
            throw new \RuntimeException('Invalid connection type: ' . $type);
        }
        $this->type = $type;
        $this->isActive = (bool) $isActive;
    }

    /**
     * @return int
     */
    public function getRevisionNr()
    {
        return $this->revisionNr;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->isActive;
    }
}
