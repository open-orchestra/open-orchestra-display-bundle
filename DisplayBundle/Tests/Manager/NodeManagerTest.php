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
    protected $alias0Language = 'fakeAlias0Language';
    protected $alias1Language = 'fakeAlias1Language';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $aliases = new ArrayCollection();
        $alias0 = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteAliasInterface');
        Phake::when($alias0)->getLanguage()->thenReturn($this->alias0Language);
        $aliases->add($alias0);
        $alias1 = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteAliasInterface');
        Phake::when($alias1)->getLanguage()->thenReturn($this->alias1Language);
        $aliases->add($alias1);

        $site = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteInterface');
        Phake::when($site)->getSiteId()->thenReturn($this->siteId);
        Phake::when($site)->getAliases()->thenReturn($aliases);

        $node = Phake::mock('OpenOrchestra\ModelInterface\Model\ReadNodeInterface');

        $this->nodeRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface');
        Phake::when($this->nodeRepository)->findPublishedInLastVersion(Phake::anyParameters())->thenReturn($node);
        $this->siteRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\ReadSiteRepositoryInterface');
        Phake::when($this->siteRepository)->findOneBySiteId(Phake::anyParameters())->thenReturn($site);

        $this->currentSiteManager = Phake::mock('OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface');
        Phake::when($this->currentSiteManager)->getCurrentSiteId()->thenReturn($this->currentSiteId);
        Phake::when($this->currentSiteManager)->getCurrentSiteDefaultLanguage()->thenReturn($this->currentSiteDefaultLanguage);

        $this->manager = new NodeManager($this->nodeRepository, $this->siteRepository, $this->currentSiteManager);
    }

    /**
     * @param array $parameters
     *
     * @dataProvider provideParameters
     */
    public function testGetNodeRouteNameWithParameters(array $parameters, array $expectedParameters)
    {

        $this->manager->getNodeRouteNameWithParameters($parameters);

        Phake::verify($this->nodeRepository)->findPublishedInLastVersion($expectedParameters[0], $expectedParameters[1], $expectedParameters[2]);
    }

    /**
     * @return array
     */
    public function provideParameters()
    {
        return array(
            array(array('id' => $this->nodeId), array($this->nodeId, $this->currentSiteDefaultLanguage, $this->currentSiteId)),
            array(array('id' => $this->nodeId, 'site' => $this->siteId), array($this->nodeId, $this->alias0Language, $this->siteId)),
            array(array('id' => $this->nodeId, 'site' => $this->siteId, 'aliasId' => 1), array($this->nodeId, $this->alias1Language, $this->siteId)),
        );
    }
}
