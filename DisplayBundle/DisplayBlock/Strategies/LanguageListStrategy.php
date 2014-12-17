<?php

namespace PHPOrchestra\DisplayBundle\DisplayBlock\Strategies;

use PHPOrchestra\BaseBundle\Context\CurrentSiteIdInterface;
use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelBundle\Repository\SiteRepository;
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
     * @param FormFactory            $formFactory
     * @param CurrentSiteIdInterface $currentSiteIdInterface
     * @param SiteRepository         $siteRepository
     * @param RequestStack           $requestStack
     */
    public function __construct(
        FormFactory $formFactory,
        CurrentSiteIdInterface $currentSiteIdInterface,
        SiteRepository $siteRepository,
        RequestStack $requestStack
    )
    {
        $this->builder = $formFactory->createBuilder('form');
        $this->currentSiteIdInterface = $currentSiteIdInterface;
        $this->siteRepository = $siteRepository;
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
            'data' => $this->request->getLocale(),
            'preferred_choices' => array($this->currentSiteIdInterface->getCurrentSiteDefaultLanguage()),
        ))
        ->getForm();

        return $this->render(
            'PHPOrchestraDisplayBundle:Block/LanguageList:show.html.twig',
            array(
                'class' => array_key_exists('class', $attributes)? $attributes['class']: '',
                'id' => array_key_exists('class', $attributes)? $attributes['id']: '',
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
