<?php

namespace PHPOrchestra\DisplayBundle\Test\Twig;

use Phake;
use PHPOrchestra\DisplayBundle\Twig\NavigatorExtension;

/**
 * Class NavigatorExtensionTest
 */
class NavigatorExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $navigator;
    protected $translator;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->translator = Phake::mock('Symfony\Component\Translation\TranslatorInterface');
        Phake::when($this->translator)->trans('php_orchestra_display.twig.navigator.first')->thenReturn('First');
        Phake::when($this->translator)->trans('php_orchestra_display.twig.navigator.previous')->thenReturn('Previous');
        Phake::when($this->translator)->trans('php_orchestra_display.twig.navigator.next')->thenReturn('Next');
        Phake::when($this->translator)->trans('php_orchestra_display.twig.navigator.last')->thenReturn('Last');

        $this->navigator = new NavigatorExtension($this->translator);
    }

    /**
     * test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Twig_Extension', $this->navigator);
    }

    /**
     * Test return
     * 
     * @dataProvider provideNavParameters
     */
    public function testRenderNav($nbPages, $curPage, $params, $maxPages, $expected)
    {
        $navigator = $this->navigator->renderNav($nbPages, $curPage, $params, $maxPages);
        $this->assertSame($navigator, $expected);
    }

    /**
     * Provide nav parameters
     */
    public function provideNavParameters()
    {
        $full = '<a href="?var1=val1&var2=val2&page=1" class="navigatorShortcut1">First</a> <a href="?var1=val1&var2=val2&page=4 "class="navigatorShortcut2">Previous</a> ...  <a href="?var1=val1&var2=val2&page=4" class="navigatorPage">4</a> <span class="navigatorCurrent">5</span>  <a href="?var1=val1&var2=val2&page=6" class="navigatorPage">6</a> ... <a href="?var1=val1&var2=val2&page=6" class="navigatorShortcut2">Next</a> <a href="?var1=val1&var2=val2&page=10" class="navigatorShortcut1">Last</a>';
        $firstPage = '<span class="navigatorCurrent">1</span>  <a href="?var1=val1&page=2" class="navigatorPage">2</a>  <a href="?var1=val1&page=3" class="navigatorPage">3</a> ... <a href="?var1=val1&page=2" class="navigatorShortcut2">Next</a> <a href="?var1=val1&page=10" class="navigatorShortcut1">Last</a>';
        $lastPage = '<a href="?var2=val2&page=1" class="navigatorShortcut1">First</a> <a href="?var2=val2&page=9 "class="navigatorShortcut2">Previous</a> ...  <a href="?var2=val2&page=8" class="navigatorPage">8</a>  <a href="?var2=val2&page=9" class="navigatorPage">9</a> <span class="navigatorCurrent">10</span>';
        $onePage = '<span class="navigatorCurrent">1</span>';

        return array(
            array(10, 5, array('var1' => 'val1', 'var2' => 'val2'), 1, $full),
            array(10, 1, array('var1' => 'val1'), 2, $firstPage),
            array(10, 10, array('page' => 10, 'var2' => 'val2'), 2, $lastPage),
            array(1, 1, array(), 2, $onePage),
        );
    }
}
