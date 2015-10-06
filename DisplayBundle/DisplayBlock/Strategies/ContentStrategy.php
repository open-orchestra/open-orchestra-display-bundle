<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BBcodeBundle\Parser\BBcodeParserInterface;
use OpenOrchestra\DisplayBundle\Exception\ContentNotFoundException;
use OpenOrchestra\DisplayBundle\Fake\FakeContent;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\ModelInterface\Model\ReadContentInterface;
use OpenOrchestra\ModelInterface\Repository\ReadContentRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class ContentStrategy
 */
class ContentStrategy extends AbstractStrategy
{
    const CONTENT = 'content';

    protected $contentRepository;
    protected $tagManager;
    protected $requestStack;
    protected $parser;

    /**
     * @param ReadContentRepositoryInterface $contentRepository
     * @param RequestStack                   $requestStack
     * @param TagManager                     $tagManager
     * @param BBcodeParserInterface          $parser
     */
    public function __construct(
        ReadContentRepositoryInterface $contentRepository,
        RequestStack $requestStack,
        TagManager $tagManager,
        BBcodeParserInterface $parser
    ){
        $this->contentRepository = $contentRepository;
        $this->requestStack = $requestStack;
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
        return self::CONTENT == $block->getComponent();
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
     * @param ReadBlockInterface $block
     *
     * @return Response
     *
     * @throws ContentNotFoundException
     */
    public function show(ReadBlockInterface $block)
    {
        $contentId = $this->requestStack->getCurrentRequest()->get('contentId');

        $content = $this->getContent($contentId);

        if (!is_null($content)) {
            $contentTemplate = $block->getAttribute('contentTemplate');
            $this->parser->parse($contentTemplate);
            $contentTemplate = $this->parser->getAsHTML();

            return $this->render(
                'OpenOrchestraDisplayBundle:Block/Content:show.html.twig',
                array(
                    'content' => $content,
                    'class' => $block->getClass(),
                    'id' => $block->getId(),
                    'contentTemplateEnabled' => $block->getAttribute('contentTemplateEnabled'),
                    'contentTemplate' => $contentTemplate,
                )
            );
        }

        throw new ContentNotFoundException($contentId);
    }

    /**
     * Get content to display
     *
     * @param string $contentId
     *
     * @return ReadContentInterface
     */
    protected function getContent($contentId)
    {
        $content = null;
        if (!is_null($contentId)) {
            $language = $this->currentSiteManager->getCurrentSiteDefaultLanguage();
            $content = $this->contentRepository->findLastPublishedVersion($contentId, $language);
        }

        if (is_null($content) && $this->requestStack->getMasterRequest()->get('token')) {
            $content = new FakeContent();
        }

        return $content;
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
        $contentId = $this->requestStack->getCurrentRequest()->get('contentId');

        $content = $this->getContent($contentId);

        return array(
            $this->tagManager->formatContentIdTag($contentId),
            $this->tagManager->formatContentTypeTag($content->getContentType()),
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'content';
    }
}
