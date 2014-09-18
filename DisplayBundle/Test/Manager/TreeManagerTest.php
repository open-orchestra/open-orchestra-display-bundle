<?php

namespace PHPOrchestra\DisplayBundle\Test\Manager;

use Phake;
use PHPOrchestra\DisplayBundle\Manager\TreeManager;

/**
 * Class TreeManagerTest
 */
class TreeManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TreeManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new TreeManager();
    }

    /**
     * @param array $nodes
     * @param array $tree
     *
     * @dataProvider provideNodesAndTrees
     */
    public function testGenerateTree($nodes, $tree)
    {
        $generateTree = $this->manager->generateTree($nodes);

        $this->assertSame($tree, $generateTree);
    }

    /**
     * @return array
     */
    public function provideNodesAndTrees()
    {
        $rootNodeId = 'rootNodeId';
        $rootParentId = 'root';
        $childNodeId = 'childNodeId';
        $otherChildNodeId = 'otherChildNodeId';
        $grandChildNodeId = 'grandChildNodeId';
        $brotherGrandChildNodeId = 'brotherGrandChildNodeId';
        $otherGrandChildNodeId = 'otherGrandChildNodeId';
        $grandGrandChildNodeId = 'grandGrandChildNodeId';
        $otherGrandGrandChildNodeId = 'otherGrandGrandChildNodeId';
        $fourthDescendantNodeId = 'fourthDescendantNodeId';

        $superRootNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($superRootNode)->getNodeId()->thenReturn($rootParentId);
        Phake::when($superRootNode)->getParentId()->thenReturn('-');

        $rootNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($rootNode)->getNodeId()->thenReturn($rootNodeId);
        Phake::when($rootNode)->getParentId()->thenReturn($rootParentId);

        $childNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($childNode)->getNodeId()->thenReturn($childNodeId);
        Phake::when($childNode)->getParentId()->thenReturn($rootNodeId);

        $otherChildNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($otherChildNode)->getNodeId()->thenReturn($otherChildNodeId);
        Phake::when($otherChildNode)->getParentId()->thenReturn($rootNodeId);

        $grandChildNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($grandChildNode)->getNodeId()->thenReturn($grandChildNodeId);
        Phake::when($grandChildNode)->getParentId()->thenReturn($childNodeId);

        $otherGrandChildNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($otherGrandChildNode)->getNodeId()->thenReturn($otherGrandChildNodeId);
        Phake::when($otherGrandChildNode)->getParentId()->thenReturn($otherChildNodeId);

        $grandGrandChildNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($grandGrandChildNode)->getNodeId()->thenReturn($grandGrandChildNodeId);
        Phake::when($grandGrandChildNode)->getParentId()->thenReturn($grandChildNodeId);

        $otherGrandGrandChildNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($otherGrandGrandChildNode)->getNodeId()->thenReturn($otherGrandGrandChildNodeId);
        Phake::when($otherGrandGrandChildNode)->getParentId()->thenReturn($otherGrandChildNodeId);

        $fourthDescendantNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($fourthDescendantNode)->getNodeId()->thenReturn($fourthDescendantNodeId);
        Phake::when($fourthDescendantNode)->getParentId()->thenReturn($grandGrandChildNodeId);

        $brotherGrandChildNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($brotherGrandChildNode)->getNodeId()->thenReturn($brotherGrandChildNodeId);
        Phake::when($brotherGrandChildNode)->getParentId()->thenReturn($otherChildNodeId);

        return array(
            array(array(), array()),
            array(array($rootNode), array(array('node' => $rootNode, 'child' => array()))),
            array(array($rootNode, $rootNode), array(
                array('node' => $rootNode, 'child' => array()),
                array('node' => $rootNode, 'child' => array()),
            )),
            array(array($rootNode, $childNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array())
                ))
            )),
            array(array($rootNode, $childNode, $childNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array()),
                    array('node' => $childNode, 'child' => array())
                ))
            )),
            array(array($rootNode, $childNode, $grandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array())
                    )),
                ))
            )),
            array(array($grandChildNode, $rootNode, $childNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array())
                    )),
                ))
            )),
            array(array($grandChildNode, $childNode, $rootNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array())
                    )),
                ))
            )),
            array(array($childNode, $grandChildNode, $rootNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array())
                    )),
                ))
            )),
            array(array($rootNode, $childNode, $grandChildNode, $grandGrandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array())
                        ))
                    )),
                ))
            )),
            array(array($grandGrandChildNode, $rootNode, $childNode, $grandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array())
                        ))
                    )),
                ))
            )),
            array(array($childNode, $grandGrandChildNode, $rootNode, $grandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array())
                        ))
                    )),
                ))
            )),
            array(array($grandChildNode, $childNode, $grandGrandChildNode, $rootNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array())
                        ))
                    )),
                ))
            )),
            array(array($grandChildNode, $grandGrandChildNode, $childNode, $rootNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array())
                        ))
                    )),
                ))
            )),
            array(array($grandChildNode, $grandGrandChildNode, $childNode, $rootNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array())
                        ))
                    )),
                ))
            )),
            array(array($grandGrandChildNode, $rootNode), array(
                array('node' => $grandGrandChildNode, 'child' => array()),
                array('node' => $rootNode, 'child' => array()),
            )),
            array(array($grandGrandChildNode), array(
                array('node' => $grandGrandChildNode, 'child' => array())
            )),
            array(array($grandGrandChildNode, $superRootNode), array(
                array('node' => $grandGrandChildNode, 'child' => array()),
                array('node' => $superRootNode, 'child' => array()),
            )),
            array(array($rootNode, $childNode, $grandChildNode, $grandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array()),
                        array('node' => $grandChildNode, 'child' => array()),
                    ))
                )),
            )),
            array(array($rootNode, $otherChildNode, $childNode, $grandChildNode, $grandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $otherChildNode, 'child' => array()),
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array()),
                        array('node' => $grandChildNode, 'child' => array()),
                    )),
                )),
            )),
            array(array($rootNode, $otherGrandGrandChildNode, $otherChildNode, $otherGrandChildNode, $grandGrandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $otherChildNode, 'child' => array(
                        array('node' => $otherGrandChildNode, 'child' => array(
                            array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    ))
                )),
                array('node' => $grandGrandChildNode, 'child' => array()),
            )),
            array(array($rootNode, $otherGrandGrandChildNode, $grandChildNode, $otherChildNode, $otherGrandChildNode, $grandGrandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $otherChildNode, 'child' => array(
                        array('node' => $otherGrandChildNode, 'child' => array(
                            array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    ))
                )),
                array('node' => $grandChildNode, 'child' => array(
                    array('node' => $grandGrandChildNode, 'child' => array()),
                )),
            )),
            array(array($rootNode, $otherGrandGrandChildNode, $grandChildNode, $otherChildNode, $otherGrandChildNode, $childNode, $grandGrandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $otherChildNode, 'child' => array(
                        array('node' => $otherGrandChildNode, 'child' => array(
                            array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    )),
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array()),
                        )),
                    )),
                )),
            )),
            array(array($fourthDescendantNode, $rootNode, $otherGrandGrandChildNode, $grandChildNode, $otherChildNode, $otherGrandChildNode, $childNode, $grandGrandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $otherChildNode, 'child' => array(
                        array('node' => $otherGrandChildNode, 'child' => array(
                            array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    )),
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array(
                            array('node' => $grandGrandChildNode, 'child' => array(
                                array('node' => $fourthDescendantNode, 'child' => array())
                            )),
                        )),
                    )),
                )),
            )),
            array(array(
                $rootNode, $brotherGrandChildNode, $otherGrandGrandChildNode, $otherChildNode, $otherGrandChildNode
            ), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $otherChildNode, 'child' => array(
                        array('node' => $brotherGrandChildNode, 'child' => array()),
                        array('node' => $otherGrandChildNode, 'child' => array(
                            array('node' => $otherGrandGrandChildNode, 'child' => array())
                        )),
                    )),
                )),
            )),
        );
    }
}
