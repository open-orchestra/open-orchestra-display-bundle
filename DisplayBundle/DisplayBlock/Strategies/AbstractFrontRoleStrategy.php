<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\FrontBundle\Security\ContributionActionInterface;
use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class AbstractFrontRoleStrategy
 */
abstract class AbstractFrontRoleStrategy extends AbstractDisplayBlockStrategy
{
    protected $authorizationChecker;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker
    ){
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Get Nodes to display
     *
     * @param array $nodes
     *
     * @return array
     */
    protected function getGrantedNodes(array $nodes)
    {
        foreach ($nodes as $key => $node) {
            if (!$this->isGrantedNode($node)) {
                unset($nodes[$key]);
            }
        }

        return $nodes;
    }

    protected function isGrantedNode(ReadNodeInterface $node) {
        return empty($node->getFrontRoles()) ||
            ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') &&
            $this->authorizationChecker->isGranted(ContributionActionInterface::READ, $node));
    }
}
