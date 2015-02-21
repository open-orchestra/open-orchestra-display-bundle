<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use OpenOrchestra\ModelInterface\Model\NodeInterface;

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
            if ($superRoot !== $node->getParentId() && $this->parentInList($node->getParentId(), $nodes)) {
                $list[$node->getParentId()][] = $node;
                continue;
            }
            $list[$superRoot][] = $node;
        }

        $tree = $this->createTree($list[$superRoot], $list);

        return $tree;
    }

    /**
     * @param array|NodeInterface $nodes
     * @param array               $list
     *
     * @return array
     */
    protected function createTree($nodes, $list)
    {
        $tree = array();

        if (is_array($nodes)) {
            foreach ($nodes as $node) {
                $position = $this->getNodePosition($node, $tree);
                $tree[$position] = array('node' => $node, 'child' => $this->getChild($node, $list));
            }
            $tree = $this->sortArray($tree);
        } elseif (!empty($nodes)) {
            $tree = array('node' => $nodes, 'child' => $this->getChild($nodes, $list));
        }

        return $tree;
    }

    /**
     * @param NodeInterface $node
     * @param array         $list
     *
     * @return array
     */
    protected function getChild(NodeInterface $node, $list)
    {
        $childs = array();

        if (!empty($list[$node->getNodeId()]) && is_array($list[$node->getNodeId()])) {
            foreach ($list[$node->getNodeId()] as $child) {
                $position = $this->getNodePosition($child, $childs);
                $childs[$position] = $this->createTree($child, $list);
            }
        }

        $childs = $this->sortArray($childs);

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

    /**
     * @param NodeInterface $node
     * @param array         $tree
     *
     * @return mixed
     */
    protected function getNodePosition($node, $tree)
    {
        $position = $node->getOrder();
        while (array_key_exists($position, $tree)) {
            $position++;
        }

        return $position;
    }

    /**
     * @param array $tree
     *
     * @return mixed
     */
    protected function sortArray($tree)
    {
        if (!empty($tree)) {
            ksort($tree);
        }

        return $tree;
    }
}
