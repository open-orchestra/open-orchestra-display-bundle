<?php

namespace OpenOrchestra\DisplayBundle\Test\Routing;

use Phake;
use OpenOrchestra\DisplayBundle\Routing\PhpOrchestraUrlGenerator;
use OpenOrchestra\ModelInterface\Model\NodeInterface;

/**
 * Tests of PhpOrchestraUrlGenerator
 */
class PhpOrchestraUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PhpOrchestraUrlGenerator
     */
    protected $generator;

    protected $node;
    protected $context;
    protected $request;
    protected $siteManager;
    protected $requestStack;
    protected $nodeRepsitory;
    protected $httpPort = 80;
    protected $httpsPort = 444;
    protected $defaultLanguage = 'fr';
    protected $host = 'some-site.com';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->request = Phake::mock('Symfony\Component\HttpFoundation\Request');
        Phake::when($this->request)->get(Phake::anyParameters())->thenReturn('2');
        $this->requestStack = Phake::mock('Symfony\Component\HttpFoundation\RequestStack');
        Phake::when($this->requestStack)->getMasterRequest(Phake::anyParameters())->thenReturn($this->request);
        $this->siteManager = Phake::mock('OpenOrchestra\DisplayBundle\Manager\SiteManager');
        Phake::when($this->siteManager)->getCurrentSiteDefaultLanguage()->thenReturn($this->defaultLanguage);

        $routes = Phake::mock('Symfony\Component\Routing\RouteCollection');
        Phake::when($routes)->get(Phake::anyParameters())->thenReturn(null);

        $this->context = Phake::mock('Symfony\Component\Routing\RequestContext');
        Phake::when($this->context)->getHttpPort(Phake::anyParameters())->thenReturn($this->httpPort);
        Phake::when($this->context)->getHttpsPort(Phake::anyParameters())->thenReturn($this->httpsPort);
        Phake::when($this->context)->getHost(Phake::anyParameters())->thenReturn($this->host);
        Phake::when($this->context)->getParameter('_locale')->thenReturn($this->defaultLanguage);

        $this->node = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        $this->nodeRepsitory = Phake::mock('OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface');
        Phake::when($this->nodeRepsitory)->findOneByNodeId(Phake::anyParameters())->thenReturn($this->node);

        $this->generator = new PhpOrchestraUrlGenerator(
            $routes,
            $this->context,
            $this->nodeRepsitory,
            $this->siteManager,
            $this->requestStack
        );
    }

    /**
     * Test generate
     *
     * @dataProvider generateDataProvider
     */
    public function testGenerate($scheme, $nodeId, $parameters, $refType, $expected)
    {
        Phake::when($this->context)->getScheme()->thenReturn($scheme);
        Phake::when($this->node)->getRoutePattern()->thenReturn($nodeId);
        Phake::when($this->node)->getParentId()->thenReturn('root');

        $uriGenerated = $this->generator->generate($nodeId, $parameters, $refType);

        $this->assertEquals($expected, $uriGenerated);
    }

    /**
     * @return array
     */
    public function generateDataProvider()
    {
        return array(
            array('http', 'page2', array(), PhpOrchestraUrlGenerator::RELATIVE_PATH, 'page2'),
            array('https', 'page', array(), PhpOrchestraUrlGenerator::ABSOLUTE_URL, 'https://some-site.com:444/page'),
            array('http', 'page1', array(), PhpOrchestraUrlGenerator::NETWORK_PATH, '//some-site.com/page1'),
            array('http', 'nodeId', array('content' => 3), PhpOrchestraUrlGenerator::ABSOLUTE_URL, 'http://some-site.com/nodeId?content=3'),
            array('http', 'pageId', array('news' => 'test'), PhpOrchestraUrlGenerator::RELATIVE_PATH, 'pageId?news=test'),
            array('http', 'contentId', array('test' => 'encore'), PhpOrchestraUrlGenerator::NETWORK_PATH, '//some-site.com/contentId?test=encore'),
            array('http', NodeInterface::ROOT_NODE_ID, array(), PhpOrchestraUrlGenerator::RELATIVE_PATH, './'),
            array('http', NodeInterface::ROOT_NODE_ID, array(), PhpOrchestraUrlGenerator::ABSOLUTE_PATH, ''),
            array('http', NodeInterface::ROOT_NODE_ID, array(), PhpOrchestraUrlGenerator::NETWORK_PATH, '//some-site.com'),
        );
    }


    /**
     * @param string $scheme
     * @param string $nodeId
     * @param string $nodeLanguage
     * @param array  $parameters
     * @param string $refType
     * @param string $expected
     *
     * @dataProvider provideDataWithLanguage
     */
    public function testGenerateWithLanguage($scheme, $nodeId, $nodeLanguage, $parameters, $refType, $expected)
    {
        Phake::when($this->context)->getScheme()->thenReturn($scheme);
        Phake::when($this->context)->getParameter('_locale')->thenReturn($nodeLanguage);
        Phake::when($this->node)->getRoutePattern()->thenReturn($nodeId);
        Phake::when($this->node)->getParentId()->thenReturn('root');

        $uriGenerated = $this->generator->generate($nodeId, $parameters, $refType);

        $this->assertEquals($expected, $uriGenerated);
    }

    /**
     * @return array
     */
    public function provideDataWithLanguage()
    {
        return array(
            array('http', 'page2', 'en', array(), PhpOrchestraUrlGenerator::RELATIVE_PATH, 'en/page2'),
            array('http', 'page2', 'fr', array(), PhpOrchestraUrlGenerator::RELATIVE_PATH, 'page2'),
            array('https', 'page', 'en', array(), PhpOrchestraUrlGenerator::ABSOLUTE_URL, 'https://some-site.com:444/en/page'),
            array('https', 'page', 'fr', array(), PhpOrchestraUrlGenerator::ABSOLUTE_URL, 'https://some-site.com:444/page'),
            array('http', 'page1', 'en', array(), PhpOrchestraUrlGenerator::NETWORK_PATH, '//some-site.com/en/page1'),
            array('http', 'nodeId', 'en', array('content' => 3), PhpOrchestraUrlGenerator::ABSOLUTE_URL, 'http://some-site.com/en/nodeId?content=3'),
            array('http', 'pageId', 'en', array('news' => 'test'), PhpOrchestraUrlGenerator::RELATIVE_PATH, 'en/pageId?news=test'),
            array('http', 'contentId', 'en', array('test' => 'encore'), PhpOrchestraUrlGenerator::NETWORK_PATH, '//some-site.com/en/contentId?test=encore'),
            array('http', NodeInterface::ROOT_NODE_ID, 'en', array(), PhpOrchestraUrlGenerator::RELATIVE_PATH, 'en'),
            array('http', NodeInterface::ROOT_NODE_ID, 'en', array(), PhpOrchestraUrlGenerator::ABSOLUTE_PATH, '/en'),
            array('http', NodeInterface::ROOT_NODE_ID, 'en', array(), PhpOrchestraUrlGenerator::NETWORK_PATH, '//some-site.com/en'),
        );
    }

    /**
     * test with parent
     *
     * @param string $alias
     * @param string $parentId
     * @param string $nodeId
     * @param string $rootId
     * @param array  $parameters
     *
     * @dataProvider provideRoutePattern
     */
    public function testGenerateWithParent($alias, $parentId, $nodeId, $rootId, $parameters)
    {

        $nodeParent = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($nodeParent)->getParentId()->thenReturn($rootId);
        Phake::when($nodeParent)->getRoutePattern()->thenReturn($alias);
        Phake::when($this->nodeRepsitory)->findOneByNodeId($parentId)->thenReturn($nodeParent);

        Phake::when($this->node)->getRoutePattern()->thenReturn($alias);
        Phake::when($this->node)->getParentId()->thenReturn($parentId);

        $uriGenerated = $this->generator->generate($nodeId, $parameters);

        if (!empty($parameters)) {
            $this->assertEquals('/' . $alias . '/' . $alias . '?' . http_build_query($parameters), $uriGenerated);
        } else {
            $this->assertEquals('/' . $alias . '/' . $alias, $uriGenerated);
        }

        Phake::verify($this->nodeRepsitory)->findOneByNodeId($nodeId);
        Phake::verify($this->node)->getParentId();
        Phake::verify($this->node)->getRoutePattern();
    }

    /**
     * @return array
     */
    public function provideRoutePattern()
    {
        return array(
            array('test', 'parent', 'node', 'root', array()),
            array('alias', 'parent', 'node', 'root', array()),
            array('other', 'parent', 'node', 'root', array('content' => 3)),
        );
    }
}
