<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractStrategy
 */
abstract class AbstractStrategy implements DisplayBlockInterface
{
    /**
     * @var DisplayBlockManager
     */
    protected $manager;

    /**
     * @var CurrentSiteIdInterface
     */
    protected $currentSiteManager;

    /**
     * @param DisplayBlockManager $manager
     */
    public function setManager(DisplayBlockManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param CurrentSiteIdInterface $currentSiteManager
     */
    public function setCurrentSiteManager(CurrentSiteIdInterface $currentSiteManager)
    {
        $this->currentSiteManager = $currentSiteManager;
    }

    /**
     * @param ReadBlockInterface $block
     * 
     * @return boolean
     */
    public function isPublic(ReadBlockInterface $block)
    {
        return false;
    }

    /**
     * @param ReadBlockInterface $block
     * 
     * @return Array
     */
    public function getCacheTags(ReadBlockInterface $block)
    {
        return array();
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    protected function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->manager->getTemplating()->renderResponse($view, $parameters, $response);
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    public function renderView($view, array $parameters = array())
    {
        return $this->manager->getTemplating()->render($view, $parameters);
    }
}
