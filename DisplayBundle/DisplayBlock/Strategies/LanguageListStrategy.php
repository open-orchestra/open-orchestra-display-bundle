<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\ModelInterface\Model\ReadBlockInterface;
use OpenOrchestra\ModelInterface\Repository\SiteRepositoryInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LanguageListStrategy
 */
class LanguageListStrategy extends AbstractStrategy
{
    const LANGUAGE_LIST = 'language_list';

    protected $currentSiteIdInterface;
    protected $siteRepository;
    protected $request;
    protected $builder;

    /**
     * @param FormFactory             $formFactory
     * @param CurrentSiteIdInterface  $currentSiteIdInterface
     * @param SiteRepositoryInterface $siteRepository
     * @param RequestStack            $requestStack
     */
    public function __construct(
        FormFactory $formFactory,
        CurrentSiteIdInterface $currentSiteIdInterface,
        SiteRepositoryInterface $siteRepository,
        RequestStack $requestStack
    )
    {
        $this->builder = $formFactory->createBuilder('form');
        $this->currentSiteIdInterface = $currentSiteIdInterface;
        $this->siteRepository = $siteRepository;
        $this->request = $requestStack->getMasterRequest();
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

        $choices = array();
        foreach ($site->getLanguages() as $language) {
            $choices[$language] = 'open_orchestra_display.language_list.'.$language;
        }

        $form = $this->builder->create('language_choice', 'choice', array(
            'choices' => $choices,
            'data' => $this->request->getLocale(),
            'preferred_choices' => array($this->currentSiteIdInterface->getCurrentSiteDefaultLanguage()),
        ))
        ->getForm();

        return $this->render(
            'OpenOrchestraDisplayBundle:Block/LanguageList:show.html.twig',
            array(
                'class' => $block->getClass(),
                'id' => $block->getId(),
                'form' => $form->createView()
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
