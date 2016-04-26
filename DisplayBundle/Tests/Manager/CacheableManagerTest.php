<?php

namespace OpenOrchestra\DisplayBundle\Tests\Manager;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use Phake;

/**
 * Test CacheableManagerTest
 */
class CacheableManagerTest extends AbstractBaseTestCase
{
    /**
     * @var CacheableManager
     */
    protected $manager;
    protected $tagHandler;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->tagHandler = Phake::mock('FOS\HttpCache\Handler\TagHandler');
        $this->manager = new CacheableManager($this->tagHandler);
    }

    /**
     * @param int    $maxAge
     * @param int    $expectedMaxAge
     * @param string $type
     * @param int    $maxAgeCount
     * @param int    $sharedMaxAgeCount
     * @param bool   $hasEsi
     *
     * @dataProvider provideMaxAge
     */
    public function testSetPublicResponseCacheParameters($maxAge, $expectedMaxAge, $type, $hasEsi, $maxAgeCount, $sharedMaxAgeCount)
    {
        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');

        $newResponse = $this->manager->setResponseCacheParameters($response, $maxAge, $type, $hasEsi);

        $this->assertSame($response, $newResponse);
        $setMethod = "set".ucfirst($type);
        if (true == method_exists($newResponse, $setMethod))
        {
            Phake::verify($newResponse)->$setMethod();
        }
        Phake::verify($newResponse, Phake::times($maxAgeCount))->setMaxAge($expectedMaxAge);
        Phake::verify($newResponse, Phake::times($sharedMaxAgeCount))->setSharedMaxAge($expectedMaxAge);
    }

    /**
     * @return array
     */
    public function provideMaxAge()
    {
        return array(
            array(300, 300, 'public', true, 0, 1),
            array(-1, 2629743, 'public', true, 0, 1),
            array(0, 0, 'public', true, 0, 1),

            array(300, 300, 'public', false, 1, 0),
            array(-1, 2629743, 'public', false, 1, 0),
            array(0, 0, 'public', false, 1, 0),

            array(300, 300, 'private', true, 1, 0),
            array(-1, 2629743, 'private', true, 1, 0),
            array(0, 0, 'private', true, 1, 0),

            array(300, 300, 'private', false, 1, 0),
            array(-1, 2629743, 'private', false, 1, 0),
            array(0, 0, 'private', false, 1, 0)
        );
    }

    /**
     * Test method addCacheTags
     */
    public function testAddCacheTags()
    {
        $tags = array('tag1', 'tag2');

        $this->manager->addCacheTags($tags);

        Phake::verify($this->tagHandler)->addTags($tags);
    }

    /**
     * @param array $tags
     * 
     * @dataProvider provideTags
     */
    public function testInvalidateTags($tags)
    {
        $this->manager->invalidateTags($tags);

        Phake::verify($this->tagHandler)->invalidateTags($tags);
    }

    /**
     * @return array
     */
    public function provideTags()
    {
        return array(
            array(array()),
            array(array('tag1')),
            array(array('tag1', 'tag2', 'tag3'))
        );
    }
}
