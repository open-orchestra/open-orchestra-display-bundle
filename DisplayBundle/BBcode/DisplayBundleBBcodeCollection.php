<?php

namespace OpenOrchestra\DisplayBundle\BBcode;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionCollectionInterface;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionFactory;

/**
 * Class DisplayBundleBBcodeCollection
 *
 */
class DisplayBundleBBcodeCollection implements BBcodeDefinitionCollectionInterface
{
    protected $definitions = array();

    /**
     * Set the BBcode definitions introduced in the display bundle
     * 
     * @param $definitionFactory
     */
    public function __construct(BBcodeDefinitionFactory $definitionFactory)
    {
        $this->definitions[] = $definitionFactory->create('div', '<div>{param}</div>');
        $this->definitions[] = $definitionFactory->create('table', '<table>{param}</table>');
        $this->definitions[] = $definitionFactory->create('tbody', '<tbody>{param}</tbody>');
        $this->definitions[] = $definitionFactory->create('td', '<td>{param}</td>');
        $this->definitions[] = $definitionFactory->create('tr', '<tr>{param}</tr>');
        $this->definitions[] = $definitionFactory->create('th', '<tr>{param}</th>');
        $this->definitions[] = $definitionFactory->create('span', '<span>{param}</span>');
        $this->definitions[] = $definitionFactory->create('pre', '<pre>{param}</pre>');
        $this->definitions[] = $definitionFactory->create('blockquote', '<blockquote>{param}</blockquote>');
        $this->definitions[] = $definitionFactory->create('sup', '<sup>{param}</sup>');
    }

    /**
     * Get an array of BBcodeDefinitionInterface
     * 
     * @return array
     */
    public function getDefinitions() {
        return $this->definitions;
    }
}