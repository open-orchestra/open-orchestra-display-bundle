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

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->manager = new CacheableManager();
    }

    /**
     * @param int $maxAge
     * @param int $expectedMaxAge
     * @param int $count
     *
     * @dataProvider provideMaxAgeAndCount
     */
    public function testSetMaxAge($maxAge, $expectedMaxAge, $count = 1)
    {
        $response = Phake::mock('Symfony\Component\HttpFoundation\Response');

        $newResponse = $this->manager->setMaxAge($maxAge, $response);

        $this->assertSame($response, $newResponse);
        Phake::verify($newResponse, Phake::times($count))->setMaxAge($expectedMaxAge);
    }

    /**
     * @return array
     */
    public function provideMaxAgeAndCount()
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
