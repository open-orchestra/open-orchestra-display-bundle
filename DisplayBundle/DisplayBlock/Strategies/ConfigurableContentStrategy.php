<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Repository\ContentRepository;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ConfigurableContentStrategy
 */
class ConfigurableContentStrategy extends AbstractStrategy
{
    protected $contentRepository;

    /**
     * @param ContentRepository $contentRepository
     */
    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
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
        return DisplayBlockInterface::CONFIGURABLE_CONTENT == $block->getComponent();
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
        
        $criteria = array(
            'contentId' => $attributes['contentId']
        );
        
        $content = $this->contentRepository->findBy($criteria);
        
        $datas = array();
        
        if (
            isset($content[0])
            && !is_null($content[0]->getAttributes())
            && !is_null($content[0]->getAttributes()->getMongoData())
        ) {
            $datas = $content[0]->getAttributes()->getMongoData();
        }
        
        return $this->render(
            'PHPOrchestraDisplayBundle:Block/ConfigurableContent:show.html.twig',
            array('content' => $content[0]->getAttributes()->getMongoData())
        );
        
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'ConfigurableContent';
    }
}
