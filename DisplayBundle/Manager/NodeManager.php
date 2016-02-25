<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use OpenOrchestra\DisplayBundle\Exception\NodeNotFoundException;
use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface;
use OpenOrchestra\ModelInterface\Repository\ReadSiteRepositoryInterface;
use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;

/**
 * Class NodeManager
 */
class NodeManager
{
    protected $nodeRepository;
    protected $siteRepository;
    protected $currentSiteManager;

    /**
     * @param ReadNodeRepositoryInterface $nodeRepository
     * @param ReadSiteRepositoryInterface $siteRepository
     * @param CurrentSiteIdInterface      $currentSiteManager
     */
    public function __construct(
        ReadNodeRepositoryInterface $nodeRepository,
        ReadSiteRepositoryInterface $siteRepository,
        CurrentSiteIdInterface      $currentSiteManager
    )
    {
        $this->nodeRepository = $nodeRepository;
        $this->siteRepository = $siteRepository;
        $this->currentSiteManager = $currentSiteManager;
    }

    /**
     * @param array  $parameters
     *
     * @return string
     * @throw NodeNotFoundException
     */
    public function getNodeRouteNameWithParameters(array $parameters)
    {
        $siteId = $this->currentSiteManager->getCurrentSiteId();
        $siteAliasId = 0;
        $language = $this->currentSiteManager->getCurrentSiteDefaultLanguage();
        if (array_key_exists('site', $parameters)) {
            $site = $this->siteRepository->findOneBySiteId($parameters['site']);
            $siteId = $site->getSiteId();
            if (array_key_exists('site-alias', $parameters)) {
                $siteAliasId = $parameters['site-alias'];
            }
            $siteAlias = $site->getAliases()[$siteAliasId];
            $language = $siteAlias->getLanguage();
        }

        $node = $this->nodeRepository->findPublishedInLastVersion($parameters['id'], $language, $siteId);

        if (!$node instanceof ReadNodeInterface) {
            throw new NodeNotFoundException();
        }

        return $node->getId();
    }
}
