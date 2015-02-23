<?php

namespace OpenOrchestra\DisplayBundle\Test\Routing;

use Phake;
use OpenOrchestra\DisplayBundle\Routing\OpenOrchestraRouter;
use Symfony\Component\Routing\RouteCollection;

/**
 * Tests of OpenOrchestraUrlRouter
 */
class OpenOrchestraRouterTest extends \PHPUnit_Framework_TestCase
{
    protected $router;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        $cacheService = Phake::mock('OpenOrchestra\BaseBundle\Cache\CacheManagerInterface');
        $nodeRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface');
        $siteManager = Phake::mock('OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface');

        $mockRoutingLoader = Phake::mock('Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader');
        Phake::when($mockRoutingLoader)->load(Phake::anyParameters())->thenReturn(new RouteCollection());

        $container = Phake::mock('Symfony\Component\DependencyInjection\ContainerInterface');
        Phake::when($container)->get('routing.loader')->thenReturn($mockRoutingLoader);
        Phake::when($container)->get('open_orchestra_model.repository.node')->thenReturn($nodeRepository);
        Phake::when($container)->get('open_orchestra_base.cache_manager')->thenReturn($cacheService);
        Phake::when($container)->get('open_orchestra.manager.current_site')->thenReturn($siteManager);
        Phake::when($container)->get('request_stack')->thenReturn($requestStack);

        $this->router = new OpenOrchestraRouter(
            $container,
            null,
            array(
                'generator_class' => 'OpenOrchestra\DisplayBundle\Routing\OpenOrchestraUrlGenerator',
                'generator_base_class' => 'OpenOrchestra\DisplayBundle\Routing\OpenOrchestraUrlGenerator',
            )
        );
    }

    /**
     * test get generator
     */
    public function testGetGenerator()
    {
        $this->assertInstanceOf(
            'OpenOrchestra\\DisplayBundle\\Routing\\OpenOrchestraUrlGenerator',
            $this->router->getGenerator()
        );
    }
}
