<?php

namespace OpenOrchestra\DisplayBundle\Test\DisplayBlock;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockManager;
use Phake;

/**
 * Test DisplayBlockManagerTest
 */
class DisplayBlockManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DisplayBlockManager
     */
    protected $manager;

    protected $templating;
    protected $wrongStrategy;
    protected $strategy;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->templating = Phake::mock('Symfony\Component\Templating\EngineInterface');

        $this->wrongStrategy = Phake::mock('OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface');
        Phake::when($this->wrongStrategy)->support(Phake::anyParameters())->thenReturn(false);
        Phake::when($this->wrongStrategy)->getName()->thenReturn('wrong');
        $this->strategy = Phake::mock('OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface');
        Phake::when($this->strategy)->support(Phake::anyParameters())->thenReturn(true);
        Phake::when($this->strategy)->getName()->thenReturn('right');

        $this->manager = new DisplayBlockManager($this->templating);
        $this->manager->addStrategy($this->wrongStrategy);
        $this->manager->addStrategy($this->strategy);
    }

    /**
     * Test get templating
     */
    public function testGetTemplating()
    {
        $this->assertSame($this->templating, $this->manager->getTemplating());
    }

    /**
     * Test show
     *
     * @param int $blockMaxAge
     * @param int $responseMaxAge
     *
     * @dataProvider provideMaxAge
     */
    public function testShow($blockMaxAge, $responseMaxAge, $callNumber = 1)
    {
        $block = Phake::mock('OpenOrchestra\ModelInterface\Model\BlockInterface');
        Phake::when($block)->getMaxAge()->thenReturn($blockMaxAge);
        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');
        Phake::when($this->strategy)->show(Phake::anyParameters())->thenReturn($response);

        $newResponse = $this->manager->show($block);

        $this->assertSame($response, $newResponse);
        Phake::verify($this->wrongStrategy, Phake::never())->show(Phake::anyParameters());
        Phake::verify($this->strategy)->show(Phake::anyParameters());
        Phake::verify($newResponse, Phake::times($callNumber))->setMaxAge($responseMaxAge);
    }

    /**
     * @return array
     */
    public function provideMaxAge()
    {
        return array(
            array(1, 1),
            array(1000, 1000),
            array(5, 5),
            array(0, Phake::anyParameters(), 0),
            array(-1, 2629743),
        );
    }
}
