<?php

namespace OpenOrchestra\DisplayBundle\Tests\Manager;

use OpenOrchestra\BaseBundle\Tests\AbstractTest\AbstractBaseTestCase;
use OpenOrchestra\DisplayBundle\Manager\NodeManager;
use Doctrine\Common\Collections\ArrayCollection;

use Phake;

/**
 * Test NodeManagerTest
 */
class NodeManagerTest extends AbstractBaseTestCase
{
    /**
     * @var NodeManager
     */
    protected $manager;
    protected $nodeRepository;
    protected $siteRepository;
    protected $currentSiteManager;
    protected $nodeId = 'fakeNodeId';
    protected $siteId = 'fakeSiteId';
    protected $currentSiteId = 'fakeCurrentSiteId';
    protected $currentSiteDefaultLanguage = 'fakeCurrentSiteDefaultLanguage';
    protected $alias1Language = 'fakeAlias1Language';
    protected $nodeMongoId = 'fakeNodeMongoId';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $aliases = new ArrayCollection();
        $alias0 = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteAliasInterface');
        Phake::when($alias0)->getLanguage()->thenReturn($this->currentSiteDefaultLanguage);
        $aliases->add($alias0);
        $alias1 = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteAliasInterface');
        Phake::when($alias1)->getLanguage()->thenReturn($this->alias1Language);
        $aliases->add($alias1);

        $site = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteInterface');
        Phake::when($site)->getSiteId()->thenReturn($this->siteId);
        Phake::when($site)->getAliases()->thenReturn($aliases);
        Phake::when($site)->getMainAlias()->thenReturn($alias0);

        $node = Phake::mock('OpenOrchestra\ModelInterface\Model\ReadNodeInterface');
        Phake::when($node)->getId()->thenReturn($this->nodeMongoId);

        $this->nodeRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface');
        Phake::when($this->nodeRepository)->findOnePublished(Phake::anyParameters())->thenReturn($node);
        $this->siteRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\ReadSiteRepositoryInterface');
        Phake::when($this->siteRepository)->findOneBySiteId(Phake::anyParameters())->thenReturn($site);

        $this->currentSiteManager = Phake::mock('OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface');
        Phake::when($this->currentSiteManager)->getCurrentSiteId()->thenReturn($this->currentSiteId);

        $this->manager = new NodeManager($this->nodeRepository, $this->siteRepository, $this->currentSiteManager);
    }

    /**
     * @param array $parameters
     *
     * @dataProvider provideParameters
     */
    public function testGetRouteDocumentName(array $parameters, array $expectedParameters, $expectedRouteDocumentName)
    {

        $routeDocumentName = $this->manager->getRouteDocumentName($parameters);
        Phake::verify($this->nodeRepository)->findOnePublished($expectedParameters[0], $expectedParameters[1], $expectedParameters[2]);
        $this->assertEquals($expectedRouteDocumentName, $routeDocumentName);
    }

    /**
     * @return array
     */
    public function provideParameters()
    {
        return array(
            array(array('site_nodeId' => $this->nodeId), array($this->nodeId, $this->currentSiteDefaultLanguage, $this->currentSiteId), '0_' . $this->nodeMongoId),
            array(array('site_nodeId' => $this->nodeId, 'site_siteId' => $this->siteId), array($this->nodeId, $this->currentSiteDefaultLanguage, $this->siteId), '0_' . $this->nodeMongoId),
            array(array('site_nodeId' => $this->nodeId, 'site_siteId' => $this->siteId, 'site_aliasId' => 1), array($this->nodeId, $this->alias1Language, $this->siteId), '1_' . $this->nodeMongoId),
        );
    }
}
