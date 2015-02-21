<?php

namespace OpenOrchestra\DisplayBundle\Test\Manager;

use Phake;
use OpenOrchestra\DisplayBundle\Manager\TreeManager;

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

        $superRootNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($superRootNode)->getNodeId()->thenReturn($rootParentId);
        Phake::when($superRootNode)->getParentId()->thenReturn('-');
        Phake::when($superRootNode)->getOrder()->thenReturn(0);

        $rootNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($rootNode)->getNodeId()->thenReturn($rootNodeId);
        Phake::when($rootNode)->getParentId()->thenReturn($rootParentId);
        Phake::when($rootNode)->getOrder()->thenReturn(0);

        $otherRootNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($otherRootNode)->getNodeId()->thenReturn($rootNodeId);
        Phake::when($otherRootNode)->getParentId()->thenReturn($rootParentId);
        Phake::when($otherRootNode)->getOrder()->thenReturn(2);

        $childNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($childNode)->getNodeId()->thenReturn($childNodeId);
        Phake::when($childNode)->getParentId()->thenReturn($rootNodeId);
        Phake::when($childNode)->getOrder()->thenReturn(0);

        $childNode2 = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($childNode2)->getNodeId()->thenReturn($childNodeId);
        Phake::when($childNode2)->getParentId()->thenReturn($rootNodeId);
        Phake::when($childNode2)->getOrder()->thenReturn(2);

        $otherChildNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($otherChildNode)->getNodeId()->thenReturn($otherChildNodeId);
        Phake::when($otherChildNode)->getParentId()->thenReturn($rootNodeId);
        Phake::when($otherChildNode)->getOrder()->thenReturn(1);

        $grandChildNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($grandChildNode)->getNodeId()->thenReturn($grandChildNodeId);
        Phake::when($grandChildNode)->getParentId()->thenReturn($childNodeId);
        Phake::when($grandChildNode)->getOrder()->thenReturn(0);

        $grandChildNode2 = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($grandChildNode2)->getNodeId()->thenReturn($grandChildNodeId);
        Phake::when($grandChildNode2)->getParentId()->thenReturn($childNodeId);
        Phake::when($grandChildNode2)->getOrder()->thenReturn(2);

        $otherGrandChildNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($otherGrandChildNode)->getNodeId()->thenReturn($otherGrandChildNodeId);
        Phake::when($otherGrandChildNode)->getParentId()->thenReturn($otherChildNodeId);
        Phake::when($otherGrandChildNode)->getOrder()->thenReturn(1);

        $grandGrandChildNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($grandGrandChildNode)->getNodeId()->thenReturn($grandGrandChildNodeId);
        Phake::when($grandGrandChildNode)->getParentId()->thenReturn($grandChildNodeId);
        Phake::when($grandGrandChildNode)->getOrder()->thenReturn(0);

        $otherGrandGrandChildNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($otherGrandGrandChildNode)->getNodeId()->thenReturn($otherGrandGrandChildNodeId);
        Phake::when($otherGrandGrandChildNode)->getParentId()->thenReturn($otherGrandChildNodeId);
        Phake::when($otherGrandGrandChildNode)->getOrder()->thenReturn(1);

        $fourthDescendantNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($fourthDescendantNode)->getNodeId()->thenReturn($fourthDescendantNodeId);
        Phake::when($fourthDescendantNode)->getParentId()->thenReturn($grandGrandChildNodeId);
        Phake::when($fourthDescendantNode)->getOrder()->thenReturn(0);

        $brotherGrandChildNode = Phake::mock('OpenOrchestra\ModelInterface\Model\NodeInterface');
        Phake::when($brotherGrandChildNode)->getNodeId()->thenReturn($brotherGrandChildNodeId);
        Phake::when($brotherGrandChildNode)->getParentId()->thenReturn($otherChildNodeId);
        Phake::when($brotherGrandChildNode)->getOrder()->thenReturn(3);

        return array(
            array(array(), array()),
            array(array($rootNode), array(array('node' => $rootNode, 'child' => array()))),
            array(array($rootNode, $otherRootNode), array(
                0 => array('node' => $rootNode, 'child' => array()),
                2 => array('node' => $otherRootNode, 'child' => array()),
            )),
            array(array($rootNode, $childNode), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array())
                ))
            )),
            array(array($rootNode, $childNode, $childNode2), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array()),
                    2 => array('node' => $childNode2, 'child' => array())
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
            array(array($rootNode, $childNode, $grandChildNode, $grandChildNode2), array(
                array('node' => $rootNode, 'child' => array(
                    array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array()),
                        2 => array('node' => $grandChildNode2, 'child' => array()),
                    ))
                )),
            )),
            array(array($rootNode, $otherChildNode, $childNode, $grandChildNode, $grandChildNode2), array(
                array('node' => $rootNode, 'child' => array(
                    0 => array('node' => $childNode, 'child' => array(
                        array('node' => $grandChildNode, 'child' => array()),
                        2 => array('node' => $grandChildNode2, 'child' => array()),
                    )),
                    1 => array('node' => $otherChildNode, 'child' => array()),
                )),
            )),
            array(array($rootNode, $otherGrandGrandChildNode, $otherChildNode, $otherGrandChildNode, $grandGrandChildNode), array(
                0 => array('node' => $rootNode, 'child' => array(
                    1 => array('node' => $otherChildNode, 'child' => array(
                        1 => array('node' => $otherGrandChildNode, 'child' => array(
                            1 => array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    ))
                )),
                1 => array('node' => $grandGrandChildNode, 'child' => array()),
            )),
            array(array($rootNode, $otherGrandGrandChildNode, $grandChildNode, $otherChildNode, $otherGrandChildNode, $grandGrandChildNode), array(
                0 => array('node' => $rootNode, 'child' => array(
                    1 => array('node' => $otherChildNode, 'child' => array(
                        1 => array('node' => $otherGrandChildNode, 'child' => array(
                            1 => array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    ))
                )),
                1 => array('node' => $grandChildNode, 'child' => array(
                    0 => array('node' => $grandGrandChildNode, 'child' => array()),
                )),
            )),
            array(array($rootNode, $otherGrandGrandChildNode, $grandChildNode, $otherChildNode, $otherGrandChildNode, $childNode, $grandGrandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    0 => array('node' => $childNode, 'child' => array(
                        0 => array('node' => $grandChildNode, 'child' => array(
                            0 => array('node' => $grandGrandChildNode, 'child' => array()),
                        )),
                    )),
                    1 => array('node' => $otherChildNode, 'child' => array(
                        1 => array('node' => $otherGrandChildNode, 'child' => array(
                            1 => array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    )),
                )),
            )),
            array(array($fourthDescendantNode, $rootNode, $otherGrandGrandChildNode, $grandChildNode, $otherChildNode, $otherGrandChildNode, $childNode, $grandGrandChildNode), array(
                array('node' => $rootNode, 'child' => array(
                    0 => array('node' => $childNode, 'child' => array(
                        0 => array('node' => $grandChildNode, 'child' => array(
                            0 => array('node' => $grandGrandChildNode, 'child' => array(
                                0 => array('node' => $fourthDescendantNode, 'child' => array())
                            )),
                        )),
                    )),
                    1 => array('node' => $otherChildNode, 'child' => array(
                        1 => array('node' => $otherGrandChildNode, 'child' => array(
                            1 => array('node' => $otherGrandGrandChildNode, 'child' => array())
                        ))
                    )),
                )),
            )),
            array(array(
                $rootNode, $brotherGrandChildNode, $otherGrandGrandChildNode, $otherChildNode, $otherGrandChildNode
            ), array(
                array('node' => $rootNode, 'child' => array(
                    1 => array('node' => $otherChildNode, 'child' => array(
                        1 => array('node' => $otherGrandChildNode, 'child' => array(
                            1 => array('node' => $otherGrandGrandChildNode, 'child' => array())
                        )),
                        3 => array('node' => $brotherGrandChildNode, 'child' => array()),
                    )),
                )),
            )),
        );
    }
}
