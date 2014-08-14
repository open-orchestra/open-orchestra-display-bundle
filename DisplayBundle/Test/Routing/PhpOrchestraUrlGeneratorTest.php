<?php

namespace PHPOrchestra\DisplayBundle\Test\Routing;

use Phake;
use PHPOrchestra\DisplayBundle\Routing\PhpOrchestraUrlGenerator;

/**
 * Tests of PhpOrchestraUrlGenerator
 */
class PhpOrchestraUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    protected $node;
    protected $context;
    protected $generator;
    protected $nodeRepsitory;
    protected $httpPort = 80;
    protected $httpsPort = 444;
    protected $host = 'some-site.com';

    /**
     * Set up the test
     */
    public function setUp()
    {
        $routes = Phake::mock('Symfony\Component\Routing\RouteCollection');
        Phake::when($routes)->get(Phake::anyParameters())->thenReturn(null);

        $this->context = Phake::mock('Symfony\Component\Routing\RequestContext');
        Phake::when($this->context)->getHttpPort(Phake::anyParameters())->thenReturn($this->httpPort);
        Phake::when($this->context)->getHttpsPort(Phake::anyParameters())->thenReturn($this->httpsPort);
        Phake::when($this->context)->getHost(Phake::anyParameters())->thenReturn($this->host);

        $this->node = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        $this->nodeRepsitory = Phake::mock('PHPOrchestra\ModelBundle\Repository\NodeRepository');
        Phake::when($this->nodeRepsitory)->findOneByNodeId(Phake::anyParameters())->thenReturn($this->node);

        $this->generator = new PhpOrchestraUrlGenerator(
            $routes,
            $this->context,
            $this->nodeRepsitory
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
        Phake::when($this->node)->getAlias()->thenReturn($nodeId);
        Phake::when($this->node)->getParentId()->thenReturn('root');

        $uriGenerated = $this->generator->generate($nodeId, $parameters, $refType);

        $this->assertEquals($expected, $uriGenerated);
        Phake::verify($this->nodeRepsitory)->findOneByNodeId($nodeId);
        Phake::verify($this->node)->getParentId();
        Phake::verify($this->node)->getAlias();
    }

    /**
     * @return array
     */
    public function generateDataProvider()
    {
        return array(
            array(
                'http',
                'page2',
                array(),
                PhpOrchestraUrlGenerator::RELATIVE_PATH,
                'page2'
            ),
            array(
                'https',
                'page',
                array(),
                PhpOrchestraUrlGenerator::ABSOLUTE_URL,
                'https://some-site.com:444/page'
            ),
            array(
                'http',
                'page1',
                array(),
                PhpOrchestraUrlGenerator::NETWORK_PATH,
                '//some-site.com/page1'
            ),
            array(
                'http',
                'nodeId',
                array('content' => 3),
                PhpOrchestraUrlGenerator::ABSOLUTE_URL,
                'http://some-site.com/nodeId?content=3'
            ),
            array(
                'http',
                'pageId',
                array('news' => 'test'),
                PhpOrchestraUrlGenerator::RELATIVE_PATH,
                'pageId?news=test'
            ),
            array(
                'http',
                'contentId',
                array('test' => 'encore'),
                PhpOrchestraUrlGenerator::NETWORK_PATH,
                '//some-site.com/contentId?test=encore'
            ),
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
     * @dataProvider provideAlias
     */
    public function testGenerateWithParent($alias, $parentId, $nodeId, $rootId, $parameters)
    {

        $nodeParent = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($nodeParent)->getParentId()->thenReturn($rootId);
        Phake::when($nodeParent)->getAlias()->thenReturn($alias);
        Phake::when($this->nodeRepsitory)->findOneByNodeId($parentId)->thenReturn($nodeParent);

        Phake::when($this->node)->getAlias()->thenReturn($alias);
        Phake::when($this->node)->getParentId()->thenReturn($parentId);

        $uriGenerated = $this->generator->generate($nodeId, $parameters);

        if (!empty($parameters)) {
            $this->assertEquals('/' . $alias . '/' . $alias . '?' . http_build_query($parameters), $uriGenerated);
        } else {
            $this->assertEquals('/' . $alias . '/' . $alias, $uriGenerated);
        }

        Phake::verify($this->nodeRepsitory)->findOneByNodeId($nodeId);
        Phake::verify($this->node)->getParentId();
        Phake::verify($this->node)->getAlias();
    }

    /**
     * @return array
     */
    public function provideAlias()
    {
        return array(
            array(
                'test',
                'parent',
                'node',
                'root',
                array()
            ),
            array(
                'alias',
                'parent',
                'node',
                'root',
                array()
            ),
            array(
                'other',
                'parent',
                'node',
                'root',
                array('content' => 3)
            ),
        );
    }
}
