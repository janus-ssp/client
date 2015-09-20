<?php

namespace OpenConext\JanusClient;

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

    public function getAttributeAllowedValues($attributeName)
    {
        return $this->attibuteValues[$attributeName];
    }

    public function setAttributeValue($attributeName, $attributeValue)
    {
        if (!isset($this->attibuteValues[$attributeName])) {
            $this->attibuteValues[$attributeName] = array();
        }

        $this->attibuteValues[$attributeName][$attributeValue];

        return $this;
    }

    public function toArray()
    {
        return $this->attibuteValues;
    }
}
