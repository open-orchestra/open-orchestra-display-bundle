<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\FrontBundle\Security\ContributionActionInterface;
use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class AbstractAuthorizationCheckerStrategy
 */
abstract class AbstractAuthorizationCheckerStrategy extends AbstractDisplayBlockStrategy
{
    protected $authorizationChecker;
    protected $tokenStorage;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenStorageInterface         $tokenStorage
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    ){
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
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
        /**
         * @var ReadNodeInterface $node
         */
        foreach ($nodes as $key => $node) {
            if (!$this->isGrantedNode($node)) {
                unset($nodes[$key]);
            }
        }

        return $nodes;
    }

    /**
     * @param ReadNodeInterface $node
     *
     * @return bool
     */
    protected function isGrantedNode(ReadNodeInterface $node)
    {
        return empty($node->getFrontRoles()) ||
               (null !== $this->tokenStorage->getToken() && $this->authorizationChecker->isGranted(ContributionActionInterface::READ, $node));
    }
}
