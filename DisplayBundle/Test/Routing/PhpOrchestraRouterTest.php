<?php

namespace PHPOrchestra\DisplayBundle\Test\Routing;

use Phake;
use PHPOrchestra\DisplayBundle\Routing\PhpOrchestraRouter;
use Symfony\Component\Routing\RouteCollection;

/**
 * Tests of PhpOrchestraUrlRouter
 */
class PhpOrchestraRouterTest extends \PHPUnit_Framework_TestCase
{
    protected $router;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $cacheService = Phake::mock('PHPOrchestra\BaseBundle\Cache\CacheManagerInterface');
        $nodeRepository = Phake::mock('PHPOrchestra\ModelInterface\Repository\NodeRepositoryInterface');
        $siteManager = Phake::mock('PHPOrchestra\BaseBundle\Context\CurrentSiteIdInterface');

        $mockRoutingLoader = Phake::mock('Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader');
        Phake::when($mockRoutingLoader)->load(Phake::anyParameters())->thenReturn(new RouteCollection());

        $container = Phake::mock('Symfony\Component\DependencyInjection\ContainerInterface');
        Phake::when($container)->get('routing.loader')->thenReturn($mockRoutingLoader);
        Phake::when($container)->get('php_orchestra_model.repository.node')->thenReturn($nodeRepository);
        Phake::when($container)->get('php_orchestra_base.cache_manager')->thenReturn($cacheService);
        Phake::when($container)->get('php_orchestra.manager.current_site')->thenReturn($siteManager);

        $this->router = new PhpOrchestraRouter(
            $container,
            null,
            array(
                'generator_class' => 'PHPOrchestra\DisplayBundle\Routing\PhpOrchestraUrlGenerator',
                'generator_base_class' => 'PHPOrchestra\DisplayBundle\Routing\PhpOrchestraUrlGenerator',
            )
        );
    }

    /**
     * test get generator
     */
    public function testGetGenerator()
    {
        $this->assertInstanceOf(
            'PHPOrchestra\\DisplayBundle\\Routing\\PhpOrchestraUrlGenerator',
            $this->router->getGenerator()
        );
    }
}
