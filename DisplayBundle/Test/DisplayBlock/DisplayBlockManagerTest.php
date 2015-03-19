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

    protected $strategy;
    protected $templating;
    protected $wrongStrategy;
    protected $cacheableManager;
    protected $tagManager;
    protected $blockComponentTag = 'block-component';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->cacheableManager = Phake::mock('OpenOrchestra\DisplayBundle\Manager\CacheableManager');
        $this->templating = Phake::mock('Symfony\Component\Templating\EngineInterface');

        $this->tagManager = Phake::mock('OpenOrchestra\BaseBundle\Manager\TagManager');
        Phake::when($this->tagManager)->formatBlockTypeTag(Phake::anyParameters())->thenReturn($this->blockComponentTag);

        $this->wrongStrategy = Phake::mock('OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface');
        Phake::when($this->wrongStrategy)->support(Phake::anyParameters())->thenReturn(false);
        Phake::when($this->wrongStrategy)->getName()->thenReturn('wrong');
        $this->strategy = Phake::mock('OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface');
        Phake::when($this->strategy)->support(Phake::anyParameters())->thenReturn(true);
        Phake::when($this->strategy)->getName()->thenReturn('right');

        $this->manager = new DisplayBlockManager($this->templating, $this->cacheableManager, $this->tagManager);
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
     * @param int    $blockMaxAge
     * @param string $status
     * @param array  $strategyTags
     * @param array  $expectedTags
     *
     * @dataProvider provideMaxAge
     */
    public function testShow($blockMaxAge, $status, $strategyTags, $expectedTags)
    {
        $block = Phake::mock('OpenOrchestra\ModelInterface\Model\BlockInterface');
        Phake::when($block)->getMaxAge()->thenReturn($blockMaxAge);
        Phake::when($block)->getComponent()->thenReturn('component');

        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');

        Phake::when($this->strategy)->show($block)->thenReturn($response);
        Phake::when($this->strategy)->isPublic($block)->thenReturn($status == 'public');
        Phake::when($this->strategy)->getTags($block)->thenReturn($strategyTags);

        Phake::when($this->cacheableManager)->tagResponse(Phake::anyParameters())->thenReturn($response);
        Phake::when($this->cacheableManager)->setResponseCacheParameters(Phake::anyParameters())->thenReturn($response);

        $newResponse = $this->manager->show($block);

        $this->assertSame($response, $newResponse);
        Phake::verify($this->wrongStrategy, Phake::never())->show(Phake::anyParameters());
        Phake::verify($this->strategy)->show(Phake::anyParameters());
        Phake::verify($this->cacheableManager)->tagResponse($response, $expectedTags);
        Phake::verify($this->cacheableManager)->setResponseCacheParameters($response, $blockMaxAge, $status);
    }

    /**
     * @return array
     */
    public function provideMaxAge()
    {
        return array(
            array(0, 'public', array('tag1'), array('tag1', $this->blockComponentTag)),
            array(1000, 'public', array('tag2'), array('tag2', $this->blockComponentTag)),
            array(-1, 'public', array('tag1', 'tag2'), array('tag1', 'tag2', $this->blockComponentTag)),
            array(0, 'private', array('tag1'), array('tag1', $this->blockComponentTag)),
            array(1000, 'private', array('tag2'), array('tag2', $this->blockComponentTag)),
            array(-1, 'private', array('tag1', 'tag2'), array('tag1', 'tag2', $this->blockComponentTag)),
        );
    }
}
