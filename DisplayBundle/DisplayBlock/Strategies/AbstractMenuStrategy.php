<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\FrontBundle\Security\ContributionActionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class AbstractMenuStrategy
 */
abstract class AbstractMenuStrategy extends AbstractDisplayBlockStrategy
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
            if (!empty($node->getFrontRoles())) {
                if (
                    !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') ||
                    !$this->authorizationChecker->isGranted(ContributionActionInterface::READ, $node
                )) {
                    unset($nodes[$key]);
                }
            }
        }

        return $nodes;
    }
}
