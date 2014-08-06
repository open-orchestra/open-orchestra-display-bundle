<?php

namespace PHPOrchestra\DisplayBundle\Exception;
use Exception;

/**
 * Class DisplayBlockStrategyNotFoundException
 */
class DisplayBlockStrategyNotFoundException extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct($message = "")
    {
        parent::__construct('Strategy not found for this block type : ' . $message);
    }

}
