<?php

namespace OpenOrchestra\DisplayBundle\Fake;

use OpenOrchestra\ModelInterface\Model\ContentAttributeInterface;

/**
 * Class FakeContentAttribute
 */
class FakeContentAttribute implements ContentAttributeInterface
{
    protected $name;
    protected $value;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->value = $name . ' value';
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}
