<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

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
     * @param DisplayBlockManager $manager
     */
    public function setManager(DisplayBlockManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return false;
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
}
