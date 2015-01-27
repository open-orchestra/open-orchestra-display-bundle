<?php

namespace PHPOrchestra\DisplayBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class NavigatorExtension
 */
class NavigatorExtension extends \Twig_Extension
{
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
     * @param int    $nbPages
     * @param int    $curPage
     * @param array  $queryParams
     * @param int    $maxPagesDisplayedAroundCurrent
     * 
     * @return string
     */
    public function renderNav($nbPages, $curPage = 1, $queryParams = array(), $maxPagesDisplayedAroundCurrent = 2)
    {
        $navigation = array();

        $firstPageDisplayed = 1;
        $lastPageDisplayed = (int)$nbPages;

        $leftPartUrl = $this->prepareQueryString($queryParams);

        if ($curPage > $maxPagesDisplayedAroundCurrent) {
            $firstPageDisplayed = $curPage - $maxPagesDisplayedAroundCurrent;
            $navigation[] = '<a href="' . $leftPartUrl . '1" class="navigatorShortcut1">'
                . $this->translator->trans('php_orchestra_display.twig.navigator.first')
                . '</a>';
        }

        if ($curPage > 1) {
            $navigation[] = '<a href="' . $leftPartUrl . ($curPage - 1) . ' "class="navigatorShortcut2">'
                . $this->translator->trans('php_orchestra_display.twig.navigator.previous')
                . '</a>';
        }

        if ($firstPageDisplayed > 1) {
            $navigation[] = '...';
        }

        if ($curPage < $nbPages - $maxPagesDisplayedAroundCurrent) {
            $lastPageDisplayed = $curPage + $maxPagesDisplayedAroundCurrent;
        }

        for ($i = $firstPageDisplayed; $i <= $lastPageDisplayed; $i++) {
            if ($i == $curPage) {
                $navigation[] = '<span class="navigatorCurrent">' . $i . '</span>';
            } else {
                $navigation[] = ' <a href="' . $leftPartUrl . $i . '" class="navigatorPage">' . $i . '</a>';
            }
        }

        if ($lastPageDisplayed < $nbPages) {
            $navigation[] = '...';
        }

        if ($curPage < $nbPages) {
            $navigation[]= '<a href="' . $leftPartUrl . ($curPage + 1) . '" class="navigatorShortcut2">'
                . $this->translator->trans('php_orchestra_display.twig.navigator.next')
                . '</a>';
        }

        if ($curPage < $nbPages - $maxPagesDisplayedAroundCurrent) {
            $navigation[]= '<a href="' . $leftPartUrl . $nbPages . '" class="navigatorShortcut1">'
                . $this->translator->trans('php_orchestra_display.twig.navigator.last')
                . '</a>';
        }

        return implode(' ', $navigation);
    }

    /**
     * Generate navigator queryString without page number
     * 
     * @param array  $queryParams
     * 
     * @return string
     */
    protected function prepareQueryString($queryParams)
    {
        $params = array();

        if (is_array($queryParams)) {
            foreach ($queryParams as $key => $value) {
                if ($key != 'page') {
                    $params[$key] = $value;
                }
            }
        }
        $params['page'] = '';

        return '?' . http_build_query($params);
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
