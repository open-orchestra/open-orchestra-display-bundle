<?php

namespace PHPOrchestra\DisplayBundle\Test\Manager;

use Phake;
use PHPOrchestra\DisplayBundle\Manager\SiteManager;

/**
 * Test SiteManagerTest
 */
class SiteManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SiteManager
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

        $this->manager = new SiteManager($this->requestStack);
    }

    /**
     * Test current site id
     */
    public function testGetCurrentSiteId()
    {
        $siteId = '1';
        Phake::when($this->masterRequest)->get(Phake::anyParameters())->thenReturn($siteId);

        $this->assertSame($siteId, $this->manager->getCurrentSiteId());
        $this->assertSame($siteId, $this->manager->getCurrentSiteId());
        Phake::verify($this->masterRequest)->get('siteId');
    }

    /**
     * Test set current site id
     */
    public function testSetSiteId()
    {
        $siteId = '2';
        $this->manager->setSiteId($siteId);

        $this->assertSame($siteId, $this->manager->getCurrentSiteId());
    }

    /**
     * Test get default locale
     */
    public function testGetCurrentSiteDefaultLanguage()
    {
        $locale = 'fr';
        Phake::when($this->masterRequest)->getLocale()->thenReturn($locale);

        $this->assertSame($locale, $this->manager->getCurrentSiteDefaultLanguage());
        $this->assertSame($locale, $this->manager->getCurrentSiteDefaultLanguage());
        Phake::verify($this->masterRequest)->getLocale();
    }
}
