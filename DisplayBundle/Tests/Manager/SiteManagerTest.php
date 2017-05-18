<?php

namespace OpenOrchestra\DisplayBundle\Tests\Manager;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use Phake;
use OpenOrchestra\DisplayBundle\Manager\SiteManager;

/**
 * Test SiteManagerTest
 * @deprecated
 */
class SiteManagerTest extends AbstractBaseTestCase
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

        $this->assertSame($siteId, $this->manager->getSiteId());
        $this->assertSame($siteId, $this->manager->getSiteId());
        Phake::verify($this->masterRequest)->get('siteId');
    }

    /**
     * Test set current site id
     */
    public function testSetSiteId()
    {
        $siteId = '2';
        $this->manager->setSiteId($siteId);

        $this->assertSame($siteId, $this->manager->getSiteId());
    }

    /**
     * Test get default locale
     */
    public function testGetCurrentSiteDefaultLanguage()
    {
        $locale = 'fr';
        Phake::when($this->masterRequest)->get(Phake::anyParameters())->thenReturn($locale);
        Phake::when($this->masterRequest)->getLocale()->thenReturn($locale);

        $this->assertSame($locale, $this->manager->getCurrentSiteDefaultLanguage());
        $this->assertSame($locale, $this->manager->getCurrentSiteDefaultLanguage());
        Phake::verify($this->masterRequest)->getLocale();
        Phake::verify($this->masterRequest)->get('language', $locale);
    }

    /**
     * @param string $language
     *
     * @dataProvider provideLanguage
     */
    public function testSetGetCurrentLanguage($language)
    {
        $this->manager->setLanguage($language);
        $this->assertSame($language, $this->manager->getCurrentSiteDefaultLanguage());
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
