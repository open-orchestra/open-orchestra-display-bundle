<?php

namespace OpenOrchestra\BBcodeBundle\Tests\Parser;

use OpenOrchestra\BBcodeBundle\Parser\BBcodeParser;
use Phake;

/**
 * Test BBcodeParserTest
 */
class BBcodeParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $jparser;
    protected $codeDefinitionSet;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->jparser = Phake::mock('JBBCode\Parser');
        $this->codeDefinitionSet = Phake::mock('OpenOrchestra\BBcodeBundle\Parser\ConfiguredCodeDefinitionSet');
        $this->parser = new BBcodeParser($this->jparser, $this->codeDefinitionSet);
    }

    public function testAddBBcode()
    {
        $this->parser->addBBcode('tag', 'html');

        Phake::verify($this->jparser)->addBBCode('tag', 'html', false, true, -1, null, null);
    }

    public function testGetAsHtml()
    {
        $text = 'Some Random Text';
        $this->parser->getAsHtml($text);

        Phake::verify($this->jparser)->parse($text);
        Phake::verify($this->jparser)->getAsHTML();
    }

    public function testGetAsBBCode()
    {
        $text = 'Some Random Text';
        $this->parser->getAsBBcode($text);

        Phake::verify($this->jparser)->parse($text);
        Phake::verify($this->jparser)->getAsBBCode();
    }

    public function testGetAsText()
    {
        $text = 'Some Random Text';
        $this->parser->getAsText($text);

        Phake::verify($this->jparser)->parse($text);
        Phake::verify($this->jparser)->getAsText();
    }
}