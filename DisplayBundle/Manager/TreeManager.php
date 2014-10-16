<?php

namespace PHPOrchestra\DisplayBundle\Manager;

use PHPOrchestra\ModelBundle\Document\Node;
use PHPOrchestra\ModelBundle\Model\NodeInterface;

/**
 * Class TreeManager
 */
class TreeManager
{
    /**
     * @param array $nodes
     *
     * @return array
     */
    public function generateTree($nodes)
    {
        $superRoot = count(array_filter($nodes, function ($node) {
            return '-' == $node->getParentId();
        }))? '-': 'root';

        $list = array();
        $list[$superRoot] = array();

        foreach ($nodes as $node) {
            if ( $superRoot === $node->getParentId()) {
                $list[$superRoot][] = $node;
            } else {
                if ($this->parentInList($node->getParentId(), $nodes)) {
                    $list[$node->getParentId()][] = $node;
                } else {
                    $list[$superRoot][] = $node;
                }

            }
        }

        $tree = $this->createTree($list[$superRoot], $list);

        return $tree;
    }

    /**
     * @param array $nodes
     * @param array $list
     *
     * @return array
     */
    protected function createTree($nodes, $list)
    {
        $tree = array();

        if (is_array($nodes)) {
            foreach ($nodes as $node) {
                $tree[] = array('node' => $node, 'child' => $this->getChild($node, $list));
            }
        } else {
            if (!empty($nodes)) {
                $tree = array('node' => $nodes, 'child' => $this->getChild($nodes, $list));
            }
        }

        return $tree;
    }

    /**
     * @param Node  $node
     * @param array $list
     *
     * @return array
     */
    protected function getChild($node, $list)
    {
        $childs = array();

        if (!empty($list[$node->getNodeId()]) && is_array($list[$node->getNodeId()])) {
            foreach ($list[$node->getNodeId()] as $child) {
               $childs[] = $this->createTree($child, $list);
            }
        }

        return $childs;
    }

    /**
     * @param string $parentId
     * @param array  $list
     *
     * @return bool
     */
    protected function parentInList($parentId, $list)
    {
        foreach ($list as $node) {
            if ($parentId === $node->getNodeId()) {
                return true;
            }
        }

        return false;
    }
}
