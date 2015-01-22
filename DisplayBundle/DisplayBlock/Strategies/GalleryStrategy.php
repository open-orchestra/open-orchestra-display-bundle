<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class GalleryStrategy
 */
class GalleryStrategy extends AbstractStrategy
{
    protected $request;

    public function __construct(RequestStack $requestStack)
    {
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
        return DisplayBlockInterface::GALLERY == $block->getComponent();
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
        $queryParts = explode('&', $this->request->getQueryString());
        $params = array();
        foreach ($queryParts as $parameter) {
            $explodedParameter = explode('=', $parameter);
            if (count($explodedParameter) == 2) {
                $params[$explodedParameter[0]] = $explodedParameter[1];
            }
        }

        $attributes = $block->getAttributes();

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/Gallery:show.html.twig',
            array(
                'galleryClass' => $block->getClass(),
                'galleryId' => $block->getId(),
                'pictures' => $attributes['pictures'],
                'nbColumns' => $attributes['nb_columns'],
                'thumbnailFormat' => $attributes['thumbnail_format'],
                'imageFormat' => $attributes['image_format'],
                'nbPages' => ceil(count($attributes['pictures']) / $attributes['nb_items']),
                'params' => $params,
                'curPage' => ($curPage = $this->request->get('page')) ? $curPage : 1,
                'url' => rtrim($this->request->getUri(), $this->request->getQueryString())
            )
        );
    }

    /**
     * Get the name of the strategy
     *
     * @return string
     */
    public function getName()
    {
        return 'gallery';
    }

}
