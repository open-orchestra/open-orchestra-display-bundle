<?php

namespace OpenOrchestra\DisplayBundle\Tests\Twig;

use Phake;
use OpenOrchestra\DisplayBundle\Twig\TreeHelperExtension;

/**
 * Class TreeHelperExtension
 */
class TreeHelperExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TreeHelperExtension
     */
    protected $helper;

    protected $manager;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->manager = Phake::mock('OpenOrchestra\DisplayBundle\Manager\TreeManager');

        $this->helper = new TreeHelperExtension($this->manager);
    }

    /**
     * test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Twig_Extension', $this->helper);
    }

    /**
     * Test return
     */
    public function testTreeFormatter()
    {
        $nodes = array();
        Phake::when($this->manager)->generateTree(Phake::anyParameters())->thenReturn(array());

        $return = $this->helper->treeFormatter($nodes);

        $this->assertSame($nodes, $return);
        Phake::verify($this->manager)->generateTree($nodes);
    }

    /**
     * Test functions
     */
    public function testGetFunctions()
    {
        $this->assertCount(1, $this->helper->getFunctions());
        $this->assertInstanceOf('\Twig_SimpleFunction', $this->helper->getFunctions()[0]);
    }

    /**
     * Test name
     */
    public function testGetName()
    {
        $this->assertSame('tree', $this->helper->getName());
    }
}
