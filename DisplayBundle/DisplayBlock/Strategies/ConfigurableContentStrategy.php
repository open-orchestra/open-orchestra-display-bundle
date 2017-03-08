<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BBcodeBundle\Parser\BBcodeParserInterface;
use OpenOrchestra\DisplayBundle\Exception\ContentNotFoundException;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\ModelInterface\Repository\ReadContentRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class ConfigurableContentStrategy
 */
class ConfigurableContentStrategy extends AbstractDisplayBlockStrategy
{
    const NAME = 'configurable_content';

    protected $contentRepository;
    protected $tagManager;
    protected $parser;

    /**
     * @param ReadContentRepositoryInterface $contentRepository
     * @param TagManager                     $tagManager
     * @param BBcodeParserInterface          $parser
     */
    public function __construct(ReadContentRepositoryInterface $contentRepository, TagManager $tagManager, BBcodeParserInterface $parser)
    {
        $this->contentRepository = $contentRepository;
        $this->tagManager = $tagManager;
        $this->parser = $parser;
    }

    /**
     * Check if the strategy support this block
     *
     * @param ReadBlockInterface $block
     *
     * @return boolean
     */
    public function support(ReadBlockInterface $block)
    {
        return self::NAME == $block->getComponent();
    }

    /**
     * Indicate if the block is public or private
     *
     * @param ReadBlockInterface $block
     *
     * @return bool
     */
    public function isPublic(ReadBlockInterface $block)
    {
        return true;
    }

    /**
     * Perform the show action for a block
     *
     * @param ReadBlockInterface $block
     *
     * @return Response
     *
     * @throw ContentNotFoundException
     */
    public function show(ReadBlockInterface $block)
    {
        $contentSearch = $block->getAttribute('contentSearch');

        if (!isset($contentSearch['contentId'])) {
            throw new \InvalidArgumentException();
        }

        $contentId = $contentSearch['contentId'];
        $language = $this->currentSiteManager->getCurrentSiteDefaultLanguage();
        $content = $this->contentRepository->findPublishedVersion($contentId, $language);

        if ($content) {
            $contentTemplate = $block->getAttribute('contentTemplate');
            $this->parser->parse($contentTemplate);
            $contentTemplate = $this->parser->getAsHTML();

            $parameters = array(
                'class' => $block->getStyle(),
                'id' => $block->getId(),
                'content' => $content,
                'contentTemplateEnabled' => $block->getAttribute('contentTemplateEnabled'),
                'contentTemplate' => $contentTemplate,
            );

            return $this->render(
                'OpenOrchestraDisplayBundle:Block/ConfigurableContent:show.html.twig',
                $parameters
            );
        }

        throw new ContentNotFoundException($contentId);
    }

    /**
     * Return block specific cache tags
     *
     * @param ReadBlockInterface $block
     *
     * @return array
     */
    public function getCacheTags(ReadBlockInterface $block)
    {
        return array(
            $this->tagManager->formatContentTypeTag($block->getAttribute('contentTypeId')),
            $this->tagManager->formatContentIdTag($block->getAttribute('contentId'))
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'configurable_content';
    }
}
