<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\FrontBundle\Routing\OpenOrchestraUrlGenerator;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\ModelInterface\Repository\SiteRepositoryInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class LanguageListStrategy
 */
class LanguageListStrategy extends AbstractStrategy
{
    const LANGUAGE_LIST = 'language_list';

    protected $currentSiteIdInterface;
    protected $siteRepository;
    protected $urlGenerator;
    protected $template;
    protected $request;

    /**
     * @param UrlGeneratorInterface   $urlGenerator
     * @param CurrentSiteIdInterface  $currentSiteIdInterface
     * @param SiteRepositoryInterface $siteRepository
     * @param RequestStack            $requestStack
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        CurrentSiteIdInterface $currentSiteIdInterface,
        SiteRepositoryInterface $siteRepository,
        RequestStack $requestStack,
        $template
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->currentSiteIdInterface = $currentSiteIdInterface;
        $this->siteRepository = $siteRepository;
        $this->request = $requestStack->getMasterRequest();
        $this->template = $template;
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
        return self::LANGUAGE_LIST == $block->getComponent();
    }

    /**
     * Indicate if the block is public or private
     *
     * @param ReadBlockInterface $block
     * 
     * @return boolean
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
     */
    public function show(ReadBlockInterface $block)
    {
        $site = $this->siteRepository->findOneBySiteId($this->currentSiteIdInterface->getCurrentSiteId());
        $nodeId = $this->request->get('nodeId');

        $routes = array();
        foreach ($site->getLanguages() as $language) {
            try {
                $routes[$language] = $this->urlGenerator->generate($nodeId, array(OpenOrchestraUrlGenerator::REDIRECT_TO_LANGUAGE => $language));
            } catch (ResourceNotFoundException $e) {

            }
        }

        return $this->render(
            $this->template,
            array(
                'class' => $block->getClass(),
                'id' => $block->getId(),
                'routes' => $routes,
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
        return 'language_list';
    }
}
