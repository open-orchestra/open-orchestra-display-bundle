<?php

namespace OpenOrchestra\DisplayBundle\Test\Manager;

use OpenOrchestra\DisplayBundle\Manager\CacheableManager;
use Phake;

/**
 * Test CacheableManagerTest
 */
class CacheableManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheableManager
     */
    protected $manager;
    protected $cacheManager;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->cacheManager = Phake::mock('FOS\HttpCacheBundle\CacheManager');
        $this->manager = new CacheableManager($this->cacheManager);
    }

    /**
     * @param int $maxAge
     * @param int $expectedMaxAge
     * @param int count
     *
     * @dataProvider provideMaxAge
     */
    public function testSetPublicResponseCacheParameters($maxAge, $expectedMaxAge, $count)
    {
        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');

        $newResponse = $this->manager->setResponseCacheParameters($response, $maxAge, 'public');

        $this->assertSame($response, $newResponse);
        Phake::verify($newResponse)->setPublic();
        Phake::verify($newResponse, Phake::times($count))->setMaxAge($expectedMaxAge);
        Phake::verify($newResponse, Phake::times($count))->setSharedMaxAge($expectedMaxAge);
    }

    /**
     * @param int $maxAge
     * @param int $expectedMaxAge
     *
     * @dataProvider provideMaxAge
     */
    public function testSetPrivateResponseCacheParameters($maxAge, $expectedMaxAge, $count)
    {
        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');

        $newResponse = $this->manager->setResponseCacheParameters($response, $maxAge, 'private');

        $this->assertSame($response, $newResponse);
        Phake::verify($newResponse)->setPrivate();
        Phake::verify($newResponse, Phake::times($count))->setMaxAge($expectedMaxAge);
    }

    /**
     * @return array
     */
    public function provideMaxAge()
    {
        return array(
            array(300, 300, 1),
            array(-1, 2629743, 1),
            array(0, 0, 0)
        );
    }

    /**
     */
    public function testTagResponse()
    {
        $tags = array('tag1', 'tag2');

        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');

        $this->manager->tagResponse($response, $tags);

        Phake::verify($this->cacheManager)->tagResponse($response, $tags);
    }
}
