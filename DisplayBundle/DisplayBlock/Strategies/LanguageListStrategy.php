<?php

namespace OpenOrchestra\DisplayBundle\DisplayBlock\Strategies;

use OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use OpenOrchestra\ModelInterface\Repository\SiteRepositoryInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LanguageListStrategy
 */
class LanguageListStrategy extends AbstractStrategy
{
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
     * @param BlockInterface $block
     *
     * @return boolean
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::LANGUAGE_LIST == $block->getComponent();
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
