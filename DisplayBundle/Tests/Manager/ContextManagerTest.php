<?php

namespace OpenOrchestra\DisplayBundle\Tests\Manager;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\DisplayBundle\Manager\ContextManager;
use Phake;

/**
 * Test ContextManagerTest
 */
class ContextManagerTest extends AbstractBaseTestCase
{
    /**
     * @var ContextManager
     */
    protected $manager;

    protected $requestStack;
    protected $masterRequest;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->masterRequest = Phake::mock('Symfony\Component\HttpFoundation\Request');
        $this->requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($this->requestStack)->getMasterRequest()->thenReturn($this->masterRequest);

        $this->manager = new ContextManager($this->requestStack);
    }

    /**
     * Test site id
     */
    public function testGetCurrentSiteId()
    {
        $siteId = '1';
        Phake::when($this->masterRequest)->get(Phake::anyParameters())->thenReturn($siteId);

        $this->assertSame($siteId, $this->manager->getSiteId());
        Phake::verify($this->masterRequest)->get('siteId');
    }

    /**
     * Test set site id
     */
    public function testSetSiteId()
    {
        $siteId = '2';
        $this->manager->setSiteId($siteId);

        $this->assertSame($siteId, $this->manager->getSiteId());
    }

    /**
     * Test get site language
     */
    public function testGetSiteLanguage()
    {
        $locale = 'fr';
        Phake::when($this->masterRequest)->get(Phake::anyParameters())->thenReturn($locale);
        Phake::when($this->masterRequest)->getLocale()->thenReturn($locale);

        $this->assertSame($locale, $this->manager->getSiteLanguage());
        Phake::verify($this->masterRequest)->getLocale();
        Phake::verify($this->masterRequest)->get('language', $locale);
    }

    /**
     * @param string $language
     *
     * @dataProvider provideLanguage
     */
    public function testSetSiteLanguage($language)
    {
        $this->manager->setLanguage($language);
        $this->assertSame($language, $this->manager->getSiteLanguage());
    }

    /**
     * @return array
     */
    public function provideLanguage()
    {
        return array(
            array('fr'),
            array('en'),
            array('de'),
        );
    }
}
