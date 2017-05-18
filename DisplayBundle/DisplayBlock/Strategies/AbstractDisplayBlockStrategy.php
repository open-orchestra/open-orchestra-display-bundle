<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\Manager\ContextInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractDisplayBlockStrategy
 */
abstract class AbstractDisplayBlockStrategy implements DisplayBlockInterface
{
    /**
     * @var DisplayBlockManager
     */
    protected $manager;

    /**
     * @var ContextInterface
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
     * @param ContextInterface $currentSiteManager
     */
    public function setCurrentSiteManager(ContextInterface $currentSiteManager)
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
     * @return array
     */
    abstract public function getCacheTags(ReadBlockInterface $block);

    /**
     * @return array
     */
    public function getBlockParameter()
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
