<?php

namespace OpenOrchestra\DisplayBundle\Test\Twig;

use Phake;
use OpenOrchestra\DisplayBundle\Twig\OrchestraUrlExtension;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

/**
 * Class OrchestraUrlExtensionTest
 */
class OrchestraUrlExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $urlGenerator;
    protected $orchestraUrl;

    protected $ok = 'ok';
    protected $standardException = '\Exception';
    protected $missingMandatoryParameterException = '\Symfony\Component\Routing\Exception\MissingMandatoryParametersException';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->urlGenerator = Phake::mock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');

        Phake::when($this->urlGenerator)->generate($this->ok, array())->thenReturn($this->ok);
        Phake::when($this->urlGenerator)->generate($this->standardException, array())->thenThrow(new \Exception($this->standardException));
        Phake::when($this->urlGenerator)->generate($this->missingMandatoryParameterException, array())->thenThrow(new MissingMandatoryParametersException($this->missingMandatoryParameterException));

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
     * Test url generation when no exception is raised
     * 
     * @dataProvider provideNoException
     */
    public function testOrchestraUrlWithNoException($route, $parameters, $catchException, $expected)
    {
        $url = $this->orchestraUrl->orchestraUrl($route, $parameters, $catchException);
        $this->assertSame($url, $expected);
    }

    /**
     * Provide no exception
     */
    public function provideNoException()
    {
        return array(
            array($this->ok, array(), false, $this->ok),
            array($this->ok, array(), true, $this->ok),
            array($this->missingMandatoryParameterException, array(), true, false),
        );
    }

    /**
     * Test url generation when an exception is raised
     * 
     * @dataProvider provideException
     */
    public function testOrchestraUrlWithException($route, $parameters, $catchException, $expected)
    {
        $this->setExpectedException($expected);
        $this->orchestraUrl->orchestraUrl($route, $parameters, $catchException);
    }

    /**
     * Provide exceptions
     */
    public function provideException()
    {
        return array(
            array($this->standardException, array(), false, $this->standardException),
            array($this->missingMandatoryParameterException, array(), false, $this->missingMandatoryParameterException),
            array($this->standardException, array(), true, $this->standardException),
        );
    }
}
