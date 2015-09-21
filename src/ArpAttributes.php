<?php

namespace OpenConext\JanusClient;

/**
 * Class ArpAttributes
 * @package OpenConext\JanusClient
 */
class ArpAttributes
{
    const WILDCARD = '*';

    private $attibuteValues = array();

    /**
     * ArpAttributes constructor.
     * @param array $attibuteValues
     */
    public function __construct(array $attibuteValues)
    {
        $this->attibuteValues = $attibuteValues;
    }

    /**
     * @param $attributeName
     * @return mixed
     */
    public function getAttributeAllowedValues($attributeName)
    {
        return $this->attibuteValues[$attributeName];
    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return $this
     */
    public function setAttributeValue($attributeName, $attributeValue)
    {
        if (!isset($this->attibuteValues[$attributeName])) {
            $this->attibuteValues[$attributeName] = array();
        }

        $this->attibuteValues[$attributeName][$attributeValue];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->attibuteValues;
    }
}
