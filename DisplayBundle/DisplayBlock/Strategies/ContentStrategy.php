<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\DisplayBundle\Exception\ContentNotFoundException;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use OpenOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContentStrategy
 */
class ContentStrategy extends AbstractStrategy
{
    protected $contentRepository;
    protected $request;

    /**
     * @param ContentRepositoryInterface $contentRepository
     * @param RequestStack               $requestStack
     */
    public function __construct(ContentRepositoryInterface $contentRepository, RequestStack $requestStack)
    {
        $this->contentRepository = $contentRepository;
        $this->request = $requestStack->getCurrentRequest();
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
        return DisplayBlockInterface::CONTENT == $block->getComponent();
    }

    /**
     * @param BlockInterface $block
     *
     * @return Response
     *
     * @throws ContentNotFoundException
     */
    public function show(BlockInterface $block)
    {
        $contentId = '';
        if (is_array($this->request->get('module_parameters')) && array_key_exists('contentId', $this->request->get('module_parameters'))) {

            $contentId = $this->request->get('module_parameters')['contentId'];
            $content = $this->contentRepository->findOneByContentId($contentId);

            $contentFromTemplate = null;
            if ($block->getAttribute('contentTemplateEnabled') == 1 && !is_null($block->getAttribute('contentTemplate'))) {
                $twig = new \Twig_Environment(new \Twig_Loader_String());
                $contentFromTemplate = $twig->render($block->getAttribute('contentTemplate'), array('content' => $content));
            }

            if ($content != null) {
                return $this->render(
                    'OpenOrchestraDisplayBundle:Block/Content:show.html.twig',
                    array(
                        'contentFromTemplate' => $contentFromTemplate,
                        'content' => $content,
                        'class' => $block->getClass(),
                        'id' => $block->getId(),
                    )
                );
            }
        }
        elseif ($this->request->get('token')) {
            $contentFromTemplate = null;
            if ($block->getAttribute('contentTemplateEnabled') == 1 && !is_null($block->getAttribute('contentTemplate'))) {
                $contentFromTemplate = preg_replace('/({{)(.*?)(}})/', '<span class="alert-info">$2</span>', $block->getAttribute('contentTemplate'));
            }

            $attributes = array();
            for($i = 0; $i < 10; $i++){
                $attributes[] = array('name' => 'attribute'.$i.'.name','value' => 'attribute'.$i.'.value');
            }

            return $this->render(
                'OpenOrchestraDisplayBundle:Block/Content:show.html.twig',
                array(
                    'contentFromTemplate' => $contentFromTemplate,
                    'content' => array('name' => 'name', 'attributes' => $attributes),
                    'class' => $block->getClass(),
                    'id' => $block->getId(),
                )
            );
        }

        throw new ContentNotFoundException($contentId);
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
