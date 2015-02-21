<?php

namespace OpenOrchestra\DisplayBundle\Routing;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\ModelInterface\Model\NodeInterface;
use OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PhpOrchestraUrlGenerator
 */
class PhpOrchestraUrlGenerator extends UrlGenerator
{
    protected $nodeRepository;
    protected $request;
    protected $siteManager;

    /**
     * Constructor
     *
     * @param RouteCollection         $routes
     * @param RequestContext          $context
     * @param NodeRepositoryInterface $nodeRepository
     * @param CurrentSiteIdInterface  $siteManager
     * @param RequestStack            $requestStack
     * @param LoggerInterface         $logger
     */
    public function __construct(
        RouteCollection $routes,
        RequestContext $context,
        NodeRepositoryInterface $nodeRepository,
        CurrentSiteIdInterface $siteManager,
        RequestStack $requestStack,
        LoggerInterface $logger = null
    )
    {
        $this->nodeRepository = $nodeRepository;
        $this->request = $requestStack->getMasterRequest();
        $this->siteManager = $siteManager;
        $this->context = $context;
        $this->routes = $routes;
        $this->logger = $logger;
    }

    /**
     * @param string      $name
     * @param array       $parameters
     * @param bool|string $referenceType
     *
     * @return string
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        try {
            $uri = parent::generate($name, $parameters, $referenceType);
        } catch (RouteNotFoundException $e) {
            if ($this->request) {
                try {
                    $uri = parent::generate($this->request->get('aliasId', '0') . '_' . $name, $parameters, $referenceType);
                } catch (RouteNotFoundException $e) {
                    $uri = $this->dynamicGenerate($name, $parameters, $referenceType);
                }
            } else {
                    $uri = $this->dynamicGenerate($name, $parameters, $referenceType);
            }
        }

        return $uri;
    }

    /**
     * Generate url for a OpenOrchestra node
     *
     * @param string $nodeId
     * @param array  $parameters
     * @param string $referenceType
     *
     * @deprecated use dynamic routing
     *
     * @return string
     */
    protected function dynamicGenerate($nodeId, $parameters, $referenceType)
    {
        $schemeAuthority = '';
        $url = $this->getNodeAlias($nodeId);
        if ($this->context->getParameter('_locale') != $this->siteManager->getCurrentSiteDefaultLanguage()) {
            $url = '/' . $this->context->getParameter('_locale') . $url;
        }
        $scheme = $this->context->getScheme();
        $host = $this->context->getHost();

        if (self::ABSOLUTE_URL === $referenceType || self::NETWORK_PATH === $referenceType) {
            $port = '';
            if ('http' === $scheme && 80 != $this->context->getHttpPort()) {
                $port = ':' . $this->context->getHttpPort();
            } elseif ('https' === $scheme && 443 != $this->context->getHttpsPort()) {
                $port = ':' . $this->context->getHttpsPort();
            }

            $schemeAuthority = self::NETWORK_PATH === $referenceType ? '//' : "$scheme://";
            $schemeAuthority .= $host.$port;
        }

        if (self::RELATIVE_PATH === $referenceType) {
            $url = self::getRelativePath($this->context->getPathInfo(), $url);
        } else {
            $url = $schemeAuthority . $this->context->getBaseUrl() . $url;
        }

        if (!empty($parameters)) {
            $url = $url . '?' . http_build_query($parameters);
        }

        return $url;
    }

    /**
     * return relative path to $nodeId
     *
     * @param string $nodeId
     *
     * @return string
     * @throws RouteNotFoundException
     */
    protected function getNodeAlias($nodeId)
    {
        $alias = '';

        if ($nodeId != NodeInterface::ROOT_NODE_ID) {
            $node = $this->nodeRepository->findOneByNodeId($nodeId);

            if (is_null($node)) {
                throw new RouteNotFoundException(
                    sprintf('Unable to generate a URL for the node "%s" as such node does not exist.', $nodeId)
                );
            }

            $alias = $this->getNodeAlias($node->getParentId()) . '/' . $node->getRoutePattern();
        }

        return $alias;
    }
}
