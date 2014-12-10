<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\Routing\PhpOrchestraRouter;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use PHPOrchestra\ModelBundle\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractContentListStrategy
 */
abstract class AbstractContentListStrategy extends AbstractStrategy
{
    protected $contentRepository;
    protected $router;
    protected $request;

    /**
     * @param ContentRepository  $contentRepository
     * @param PhpOrchestraRouter $router
     */
    public function __construct(ContentRepository $contentRepository, PhpOrchestraRouter $router)
    {
        $this->contentRepository = $contentRepository;
        $this->router = $router;
    }

    /**
     * Perform the show action for a block
     *
     * @param BlockInterface $block
     *
     * @return Response
     */
    public function show(BlockInterface $block)
    {
        $attributes = $block->getAttributes();
        $contents = $this->getContent($attributes);

        $parameters = array(
            'contents' => $contents,
            'class' => $attributes['class'],
            'id' => $attributes['id'],
            'url' => $this->router->generate($attributes['url']),
            'characterNumber' => $attributes['characterNumber'],
        );

        if ('' != $attributes['url']) {
            $parameters['url'] = $this->router->generate($attributes['url']);
        }

        return $this->render('PHPOrchestraDisplayBundle:Block/ContentList:show.html.twig', $parameters);
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    protected abstract function getContent($attributes);
}
