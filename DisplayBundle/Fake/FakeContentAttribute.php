<?php

namespace OpenOrchestra\DisplayBundle\Fake;

use OpenOrchestra\ModelInterface\Model\ReadContentAttributeInterface;

/**
 * Class FakeContentAttribute
 */
class FakeContentAttribute implements ReadContentAttributeInterface
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
