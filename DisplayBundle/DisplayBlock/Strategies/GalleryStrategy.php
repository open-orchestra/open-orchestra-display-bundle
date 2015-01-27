<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use PHPOrchestra\DisplayBundle\Twig\NavigatorExtension;

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
        $parameters = $this->getParameters();

        $attributes = $block->getAttributes();
        $currentPage = $this->request->get(NavigatorExtension::PARAMETER_PAGE);
        if (!$currentPage) {
            $currentPage = 1;
        }

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/Gallery:show.html.twig',
            array(
                'galleryClass' => $block->getClass(),
                'galleryId' => $block->getId(),
                'pictures' => $this->filterMedias($attributes['pictures'], $currentPage, $attributes['nb_items']),
                'numberOfColumns' => $attributes['nb_columns'],
                'thumbnailFormat' => $attributes['thumbnail_format'],
                'imageFormat' => $attributes['image_format'],
                'numberOfPages' => ($attributes['nb_items'] == 0) ? 1 : ceil(count($attributes['pictures']) / $attributes['nb_items']),
                'parameters' => $parameters,
                'currentPage' => $currentPage
            )
        );
    }

    /**
     * Generate an indexed array containing query parameters
     * formatted as (paramName => paramValue)
     * 
     * @return array
     */
    protected function getParameters()
    {
        $parameters = array();
        $queryParams = $this->request->query->all();

        if (is_array($queryParams)) {
            foreach ($queryParams as $key => $value) {
                if ($key != 'module_parameters') {
                    $parameters[$key] = $value;
                }
            }
        }

        return $parameters;
    }

    /**
     * Filter medias to display
     * 
     * @param array $medias
     * @param int   $currentPage
     * @param int   $nbItems
     * 
     * @return array
     */
    protected function filterMedias($medias, $currentPage, $nbItems)
    {
        if (0 == $nbItems) {
            return $medias;
        }

        $filteredMedias = array();
        $offset = ($currentPage - 1)* $nbItems;
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
