<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use PHPOrchestra\ModelBundle\Repository\SiteRepository;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LanguageListStrategy
 */
class LanguageListStrategy extends AbstractStrategy
{
    protected $builder;
    protected $currentSiteIdInterface;
    protected $siteRepository;

    /**
     * @param FormFactory            $formFactory
     * @param CurrentSiteIdInterface $currentSiteIdInterface
     * @param SiteRepository         $siteRepository
     */
    public function __construct(FormFactory $formFactory, CurrentSiteIdInterface $currentSiteIdInterface, SiteRepository $siteRepository)
    {
        $this->builder = $formFactory->createBuilder('form');
        $this->currentSiteIdInterface = $currentSiteIdInterface;
        $this->siteRepository = $siteRepository;
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
        $attributes = $block->getAttributes();

        $site = $this->siteRepository->findOneBySiteId($this->currentSiteIdInterface->getCurrentSiteId());

        $choices = array();
        foreach ($site->getLanguages() as $language) {
            $choices[$language] = 'php_orchestra_display.language_list.'.$language;
        }

        $form = $this->builder->create('language_choice', 'choice', array(
            'choices' => $choices,
            'preferred_choices' => array($this->currentSiteIdInterface->getCurrentSiteDefaultLanguage()),
        ))
        ->getForm();

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/LanguageList:show.html.twig',
            array(
                'class' => $attributes['class'],
                'id' => $attributes['id'],
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
