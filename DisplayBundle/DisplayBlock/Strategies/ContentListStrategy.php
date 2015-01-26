<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\DisplayBundle\Routing\PhpOrchestraRouter;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContentListStrategy
 */
class ContentListStrategy extends AbstractStrategy
{
    protected $contentRepository;
    protected $router;
    protected $request;

    /**
     * @param ContentRepositoryInterface $contentRepository
     * @param PhpOrchestraRouter         $router
     */
    public function __construct(ContentRepositoryInterface $contentRepository, PhpOrchestraRouter $router)
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
        $contents = $this->contentRepository->findByContentTypeAndChoiceTypeAndKeywords($attributes['contentType'], $attributes['choiceType'], $attributes['keywords']);

        $parameters = array(
            'contents' => $contents,
            'class' => $attributes['class'],
            'id' => $attributes['id'],
            'characterNumber' => $attributes['characterNumber'],
        );

        if ('' != $attributes['url']) {
            $parameters['url'] = $this->router->generate($attributes['url']);
        }

        return $this->render('PHPOrchestraDisplayBundle:Block/ContentList:show.html.twig', $parameters);
    }

    /**
     * Check if the strategy support this block
     *
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::CONTENT_LIST === $block->getComponent();
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'content_list';
    }
}
