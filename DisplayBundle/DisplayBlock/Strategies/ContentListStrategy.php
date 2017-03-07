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
class ContentListStrategy extends AbstractDisplayBlockStrategy
{
    const NAME= 'content_list';

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
        return self::NAME === $block->getComponent();
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
        $contents = $this->getContents($block->getAttribute('contentSearch'));

        if (!is_null($contents)) {
            $contentTemplate = $block->getAttribute('contentTemplate');
            $this->parser->parse($contentTemplate);
            $contentTemplate = $this->parser->getAsHTML();

            $parameters = array(
                'contents' => $contents,
                'class' => $block->getStyle(),
                'id' => $block->getId(),
                'characterNumber' => $block->getAttribute('characterNumber'),
                'contentTemplateEnabled' => $block->getAttribute('contentTemplateEnabled'),
                'contentTemplate' => $contentTemplate,
            );

            if ('' != $block->getAttribute('contentNodeId')) {
                $language = $this->currentSiteManager->getCurrentSiteDefaultLanguage();
                $siteId = $this->currentSiteManager->getCurrentSiteId();
                $parameters['contentNodeId'] = $this->nodeRepository->findOnePublished($block->getAttribute('contentNodeId'), $language, $siteId)->getId();
            }

            return $this->render('OpenOrchestraDisplayBundle:Block/ContentList:show.html.twig', $parameters);
        }

        throw new ContentNotFoundException();
    }

    /**
     * Return block contents
     *
     * @param array $searchCriterias
     *
     * @return array
     */
    protected function getContents($searchCriterias)
    {
        $searchCriterias = array_merge(array(
            'contentType' => '',
            'choiceType' => ReadContentRepositoryInterface::CHOICE_AND,
            'keywords' => null,
        ), $searchCriterias);
        $language = $this->currentSiteManager->getCurrentSiteDefaultLanguage();
        $siteId = $this->currentSiteManager->getCurrentSiteId();

        return $this->contentRepository->findByContentTypeAndCondition($language, $searchCriterias['contentType'], $searchCriterias['choiceType'], $searchCriterias['keywords'], $siteId);
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

        $contents = $this->getContents($block->getAttribute('contentSearch'));;

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
     * @return array
     */
    public function getBlockParameter()
    {
        return array('request.aliasId');
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
