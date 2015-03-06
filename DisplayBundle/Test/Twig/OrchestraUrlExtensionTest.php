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

    protected $ok = 'ok';
    protected $standardException = 'ko';
    protected $missingMandatoryParameterException = 'missing_mandatory_exception';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->urlGenerator = Phake::mock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');

        Phake::when($this->urlGenerator)->generate($this->ok, array())->thenReturn($this->ok);
        Phake::when($this->urlGenerator)->generate($this->standardException, array())->thenThrow(new \Exception($this->standardException));
        Phake::when($this->urlGenerator)->generate($this->missingMandatoryParameterException, array())->thenReturn(new MissingMandatoryParametersException($this->missingMandatoryParameterException));

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
     * @dataProvider provideNoException
     */
    public function testOrchestraUrlWithNoException($route, $parameters, $catchException, $expected)
    {
        $route = $this->orchestraUrl->orchestraUrl($route, $parameters, $catchException);
        $this->assertSame($route, $expected);
    }

    /**
     * Test return
     * 
     * @dataProvider provideException
     */
    public function testOrchestraUrlWithException($route, $parameters, $catchException, $expected)
    {
        try {
            $this->orchestraUrl->orchestraUrl($route, $parameters, $catchException);
        } catch (\Exception $e) {
            $this->assertSame($e->getMessage(), $expected);
        }
    }

    /**
     * Provide no exception
     */
    public function provideNoException()
    {
        return array(
            array($this->ok, array(), false, $this->ok),
            array($this->ok, array(), true, $this->ok),
//            array($this->missingMandatoryParameterException, array(), true, false),
        );
    }

    public function provideException()
    {
        return array(
            array($this->standardException, array(), false, $this->standardException),
            array($this->missingMandatoryParameterException, array(), false, $this->missingMandatoryParameterException),
            array($this->standardException, array(), true, $this->standardException),
        );
    }
}
