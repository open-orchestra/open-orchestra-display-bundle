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

    /**
     * @param RequestStack $requestStack
     */
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
        $params = $this->getParams();

        $attributes = $block->getAttributes();
        $curPage = $this->request->get('page');
        if (!$curPage) {
            $curPage = 1;
        }

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/Gallery:show.html.twig',
            array(
                'galleryClass' => $block->getClass(),
                'galleryId' => $block->getId(),
                'pictures' => $this->filterMedias($attributes['pictures'], $curPage, $attributes['nb_items']),
                'nbColumns' => $attributes['nb_columns'],
                'thumbnailFormat' => $attributes['thumbnail_format'],
                'imageFormat' => $attributes['image_format'],
                'nbPages' => ($attributes['nb_items'] == 0) ? 1 : ceil(count($attributes['pictures']) / $attributes['nb_items']),
                'params' => $params,
                'curPage' => $curPage,
                'url' => rtrim($this->request->getUri(), $this->request->getQueryString())
            )
        );
    }

    /**
     * Generate an indexed array containing query parameters
     * formatted as (paramName => paramValue)
     * 
     * @return array
     */
    protected function getParams()
    {
        $params = array();

        $queryParts = explode('&', $this->request->getQueryString());
        foreach ($queryParts as $parameter) {
            $explodedParameter = explode('=', $parameter);
            if (count($explodedParameter) == 2) {
                $params[$explodedParameter[0]] = $explodedParameter[1];
            }
        }

        return $params;
    }

    /**
     * Filter medias to display
     * 
     * @param array $medias
     * @param int   $curPage
     * @param int   $nbItems
     * 
     * @return array
     */
    protected function filterMedias($medias, $curPage, $nbItems)
    {
        if (0 == $nbItems) {
            return $medias;
        }

        $filteredMedias = array();
        $offset = ($curPage - 1)* $nbItems;
        for (
            $i = $offset;
            $i < $offset + $nbItems && isset($medias[$i]);
            $i++
        ) {
            $filteredMedias[] = $medias[$i];
        }
        return $filteredMedias;
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
