<?php

namespace OpenOrchestra\BBcodeBundle\Tests\Parser;

use Phake;
use OpenOrchestra\BBcodeBundle\Parser\BBcodeParser;
use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionCollectionInterface;

/**
 * Class BBcodeParserTest
 */
class BBcodeParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    protected $definitionFactory;

    /**
     * Set up the test
     */
    public function setup()
    {
        $this->definitionFactory = Phake::mock('OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionFactory');
        Phake::when($this->definitionFactory)->create(Phake::anyParameters())->thenReturn(Phake::mock('OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition'));

        $this->parser = new BBcodeParser($this->definitionFactory);
    }

    /**
     * @param array $validators
     * 
     * @dataProvider provideValidators
     */
    public function testLoadValidatorsFromConfiguration($validators)
    {
        $this->parser->loadValidatorsFromConfiguration($validators);
        $parserValidators = $this->parser->getValidators();

        foreach ($validators as $name => $className) {
            $this->assertArrayHasKey($name, $parserValidators);
            if (isset($parserValidators[$name])) {
                $this->assertInstanceOf($className, $parserValidators[$name]);
            }
        }
    }

    /**
     * Provide validators
     */
    public function provideValidators()
    {
        return array(
            array(array('url' => 'JBBCode\validators\UrlValidator')),
            array(array('css_color' => 'JBBCode\validators\CssColorValidator')),
            array(array('url' => 'JBBCode\validators\UrlValidator', 'css_color' => 'JBBCode\validators\CssColorValidator')),
        );
    }

    /**
     * @param $validatorCollection
     * 
     * @dataProvider provideValidatorCollection
     */
    public function testLoadValidatorsFromService($validatorCollection)
    {
        $this->parser->loadValidatorsFromService($validatorCollection);
        $parserValidators = $this->parser->getValidators();

        foreach ($validatorCollection->getValidators() as $object) {
            $this->assertArrayHasKey($object->getName(), $parserValidators);
            if (isset($parserValidators[$object->getName()])) {
                $this->assertSame($object, $parserValidators[$object->getName()]);
            }
        }
    }

    /**
     * Provide validator collection
     */
    public function provideValidatorCollection()
    {
        $validator1 = Phake::mock('OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorInterface');
        Phake::when($validator1)->getName()->thenReturn('validator1Name');

        $validator2 = Phake::mock('OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorInterface');
        Phake::when($validator2)->getName()->thenReturn('validator2Name');

        $collection1 = Phake::mock('OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorCollectionInterface');
        Phake::when($collection1)->getValidators()->thenReturn(array($validator1));

        $collection2 = Phake::mock('OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorCollectionInterface');
        Phake::when($collection2)->getValidators()->thenReturn(array($validator2));

        $collection3 = Phake::mock('OpenOrchestra\BBcodeBundle\Validator\BBcodeValidatorCollectionInterface');
        Phake::when($collection3)->getValidators()->thenReturn(array($validator1, $validator2));

        return array(
            array($collection1),
            array($collection2),
            array($collection3)
        );
    }

    /**
     * @param array   $definitions
     * @param int     $expectedCount
     * 
     * @dataProvider provideDefinition
     */
    public function testLoadDefinitionsFromConfiguration($definitions, $expectedCount)
    {
        $this->parser->loadDefinitionsFromConfiguration($definitions);

        Phake::verify($this->definitionFactory, Phake::times($expectedCount))->create(Phake::anyParameters());
        $this->assertCount($expectedCount, $this->parser->getCodes());
    }

    /**
     * Provide definition set
     */
    public function provideDefinition()
    {
        $noTag = array('html' => 'NO_TAG');
        $noHtml = array('tag' => 'NO_HTML');
        $bold = array('tag' => 'b', 'html' => '<strong>{param}</strong>');
        $italic = array('tag' => 'i', 'html' => '<em>{param}</em>');

        return array(
            array(array($noTag), 0),
            array(array($noHtml), 0),
            array(array($bold), 1),
            array(array($noTag, $bold), 1),
            array(array($italic, $noHtml), 1),
            array(array($noTag, $noHtml, $bold, $italic), 2)
        );
    }

    /**
     * @param BBcodeDefinitionCollectionInterface $collection
     * @param int                                 $expectedSize
     * 
     * @dataProvider provideDefinitionCollection
     */
    public function testLoadDefinitionsFromService(BBcodeDefinitionCollectionInterface $collection, $expectedSize)
    {
        $this->parser->loadDefinitionsFromService($collection);
        $definitions = $this->parser->getCodes();

        $this->assertCount($expectedSize, $definitions);

        foreach ($collection->getDefinitions() as $definition) {
            $this->assertContains($definition, $definitions);
        }
    }

    /**
     * Provide definition collection
     */
    public function provideDefinitionCollection()
    {
        $definition1 = Phake::mock('OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition');
        $definition2 = Phake::mock('OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition');
        $definition3 = Phake::mock('OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition');

        $collection = Phake::mock('OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinitionCollectionInterface');
        Phake::when($collection)->getDefinitions()->thenReturn(array($definition1, $definition2, $definition3));

        return array(
            array($collection, 3)
        );
    }
}
