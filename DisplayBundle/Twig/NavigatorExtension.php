<?php

namespace OpenOrchestra\DisplayBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class NavigatorExtension
 */
class NavigatorExtension extends \Twig_Extension
{
    CONST PARAMETER_PAGE = 'page';

    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('navigator', array($this, 'renderNav'), array('is_safe' => array('html'))),
        );
    }

    /**
     * Render a customizable navigation bar
     * 
     * @param int    $numberOfPages
     * @param int    $currentPage
     * @param array  $queryParameters
     * @param int    $maxPagesDisplayedAroundCurrent
     * 
     * @return string
     */
    public function renderNav($numberOfPages, $currentPage = 1, $queryParameters = array(), $maxPagesDisplayedAroundCurrent = 2)
    {
        $navigation = array();

        $firstPageDisplayed = 1;
        $lastPageDisplayed = (int)$numberOfPages;

        $leftPartUrl = $this->prepareQueryString($queryParameters);

        if ($currentPage > $maxPagesDisplayedAroundCurrent) {
            $firstPageDisplayed = $currentPage - $maxPagesDisplayedAroundCurrent;
            $navigation[] = '<a href="' . $leftPartUrl . '1" class="navigatorShortcut1">'
                . $this->translator->trans('open_orchestra_display.twig.navigator.first')
                . '</a>';
        }

        if ($currentPage > 1) {
            $navigation[] = '<a href="' . $leftPartUrl . ($currentPage - 1) . ' "class="navigatorShortcut2">'
                . $this->translator->trans('open_orchestra_display.twig.navigator.previous')
                . '</a>';
        }

        if ($firstPageDisplayed > 1) {
            $navigation[] = '...';
        }

        if ($currentPage < $numberOfPages - $maxPagesDisplayedAroundCurrent) {
            $lastPageDisplayed = $currentPage + $maxPagesDisplayedAroundCurrent;
        }

        for ($i = $firstPageDisplayed; $i <= $lastPageDisplayed; $i++) {
            if ($i == $currentPage) {
                $navigation[] = '<span class="navigatorCurrent">' . $i . '</span>';
            } else {
                $navigation[] = ' <a href="' . $leftPartUrl . $i . '" class="navigatorPage">' . $i . '</a>';
            }
        }

        if ($lastPageDisplayed < $numberOfPages) {
            $navigation[] = '...';
        }

        if ($currentPage < $numberOfPages) {
            $navigation[]= '<a href="' . $leftPartUrl . ($currentPage + 1) . '" class="navigatorShortcut2">'
                . $this->translator->trans('open_orchestra_display.twig.navigator.next')
                . '</a>';
        }

        if ($currentPage < $numberOfPages - $maxPagesDisplayedAroundCurrent) {
            $navigation[]= '<a href="' . $leftPartUrl . $numberOfPages . '" class="navigatorShortcut1">'
                . $this->translator->trans('open_orchestra_display.twig.navigator.last')
                . '</a>';
        }

        return implode(' ', $navigation);
    }

    /**
     * Generate navigator queryString without page number
     * 
     * @param array  $queryParameters
     * 
     * @return string
     */
    protected function prepareQueryString($queryParameters)
    {
        unset($queryParameters[self::PARAMETER_PAGE]);
        $queryParameters[self::PARAMETER_PAGE] = '';

        return '?' . http_build_query($queryParameters);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'navigator';
    }
}
