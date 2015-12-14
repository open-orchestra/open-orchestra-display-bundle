<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BBcodeBundle\Parser\BBcodeParserInterface;
use OpenOrchestra\DisplayBundle\Exception\ContentNotFoundException;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\ModelInterface\Repository\ReadContentRepositoryInterface;
use OpenOrchestra\ModelInterface\Repository\ReadNodeRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseBundle\Manager\TagManager;

/**
 * Class ContentListStrategy
 */
class ContentListStrategy extends AbstractStrategy
{
    const CONTENT_LIST= 'content_list';

    protected $contentRepository;
    protected $nodeRepository;
    protected $request;
    protected $tagManager;
    protected $parser;

    /**
     * @param ReadContentRepositoryInterface $contentRepository
     * @param ReadNodeRepositoryInterface    $nodeRepository
     * @param TagManager                     $tagManager
     * @param BBcodeParserInterface          $parser
     */
    public function __construct(
        ReadContentRepositoryInterface $contentRepository,
        ReadNodeRepositoryInterface $nodeRepository,
        TagManager $tagManager,
        BBcodeParserInterface $parser
    ){
        $this->contentRepository = $contentRepository;
        $this->nodeRepository = $nodeRepository;
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
        return self::CONTENT_LIST === $block->getComponent();
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
     * @throws ContentNotFoundException
     */
    public function show(ReadBlockInterface $block)
    {
        $searchCriterias = array(
            'contentType' => '',
            'choiceType' => ReadContentRepositoryInterface::CHOICE_AND,
            'keywords' => null,
        );

        $searchCriterias = array_merge($searchCriterias, $block->getAttribute('contentSearch'));

        $contents = $this->getContents($searchCriterias['contentType'], $searchCriterias['choiceType'], $searchCriterias['keywords']);

        if (!is_null($contents)) {
            $contentTemplate = $block->getAttribute('contentTemplate');
            $this->parser->parse($contentTemplate);
            $contentTemplate = $this->parser->getAsHTML();

            $parameters = array(
                'contents' => $contents,
                'class' => $block->getClass(),
                'id' => $block->getId(),
                'characterNumber' => $block->getAttribute('characterNumber'),
                'contentTemplateEnabled' => $block->getAttribute('contentTemplateEnabled'),
                'contentTemplate' => $contentTemplate,
            );

            if ('' != $block->getAttribute('contentNodeId')) {
                $language = $this->currentSiteManager->getCurrentSiteDefaultLanguage();
                $siteId = $this->currentSiteManager->getCurrentSiteId();
                $parameters['contentNodeId'] = $this->nodeRepository->findPublishedInLastVersion($block->getAttribute('contentNodeId'), $language, $siteId)->getId();
            }

            return $this->render('OpenOrchestraDisplayBundle:Block/ContentList:show.html.twig', $parameters);
        }

        throw new ContentNotFoundException();
    }

    /**
     * Return block contents
     *
     * @param string $contentType
     * @param string $choiceType
     * @param string $keyword
     *
     * @return array
     */
    protected function getContents($contentType, $choiceType, $keyword)
    {
        $language = $this->currentSiteManager->getCurrentSiteDefaultLanguage();

        return $this->contentRepository->findByContentTypeAndKeywords($language, $contentType, $choiceType, $keyword);
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
        $tags = array();

        $contents = $this->getContents($block->getAttribute('contentType'), $block->getAttribute('choiceType'), $block->getAttribute('keywords'));

        if ($contents) {

            foreach ($contents as $content) {
                $tags[] = $this->tagManager->formatContentIdTag($content->getContentId());

                $contentTypeTag = $this->tagManager->formatContentTypeTag($content->getContentType());
                if (!in_array($contentTypeTag, $tags)) {
                    $tags[] = $contentTypeTag;
                }
            }
        }

        return $tags;
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
