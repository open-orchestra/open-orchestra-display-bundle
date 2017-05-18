<?php

namespace OpenOrchestra\DisplayBundle\Manager;

use OpenOrchestra\DisplayBundle\Exception\NodeNotFoundException;
use OpenOrchestra\ModelInterface\Model\ReadNodeInterface;
use OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface;
use OpenOrchestra\ModelInterface\Repository\ReadSiteRepositoryInterface;

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
     * @param ContextInterface            $currentSiteManager
     */
    public function __construct(
        ReadNodeRepositoryInterface $nodeRepository,
        ReadSiteRepositoryInterface $siteRepository,
        ContextInterface            $currentSiteManager
    ) {
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
    public function getRouteDocumentName(array $parameters)
    {
        $siteId = array_key_exists('site', $parameters) && array_key_exists('siteId', $parameters['site']) ? $parameters['site']['siteId'] : $this->currentSiteManager->getSiteId();
        $site = $this->siteRepository->findOneBySiteId($siteId);
        $siteAlias = array_key_exists('site', $parameters) && array_key_exists('aliasId', $parameters['site'])  ? $site->getAliases()[$parameters['site']['aliasId']] : $site->getMainAlias();
        $language = $siteAlias->getLanguage();

        $node = $this->nodeRepository->findOnePublished($parameters['site']['nodeId'], $language, $siteId);

        if (!$node instanceof ReadNodeInterface) {
            throw new NodeNotFoundException();
        }

        return $site->getAliases()->indexOf($siteAlias) . '_' . $node->getId();
    }
}
