<?php

namespace OpenOrchestra\DisplayBundle\Test\Twig;

use Phake;
use OpenOrchestra\DisplayBundle\Twig\OrchestraUrlExtension;

/**
 * Class OrchestraUrlExtensionTest
 */
class OrchestraUrlExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $urlGenerator;

    protected $okRoute = 'ok';
    protected $exceptionRoute = 'ko';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->urlGenerator = Phake::mock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');

        Phake::when($this->urlGenerator)->generate(Phake::anyParameters())->thenThrow(new \Exception('My error message!'));
        Phake::when($this->urlGenerator)->generate($this->okRoute, array())->thenReturn($this->okRoute);

        $this->orchestraUrl = new OrchestraUrlExtension($this->urlGenerator);
    }

    /**
     * test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Twig_Extension', $this->orchestraUrl);
    }

    /**
     * Test return
     * 
     * @dataProvider provideParameters
     */
    public function testOrchestraUrl($route, $parameters, $expected)
    {
        $route = $this->orchestraUrl->orchestraUrl($route, $parameters);

        $this->assertSame($route, $expected);
    }

    /**
     * Provide parameters
     */
    public function provideParameters()
    {
        return array(
            array($this->okRoute, array(), 'ok'),
            array($this->exceptionRoute, array(), false),
        );
    }
}
