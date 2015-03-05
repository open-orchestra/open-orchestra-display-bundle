<?php

namespace OpenOrchestra\DisplayBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class OrchestraUrlExtension
 */
class OrchestraUrlExtension extends \Twig_Extension
{
    protected $urlGenerator;

    /**
     * @param $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('orchestraUrl', array($this, 'orchestraUrl'))
        );
    }

    /**
     * Generate a url with dynamic pattern
     *
     * @param string $route
     * @param array  $parameters
     *
     * @return string
     */
    public function orchestraUrl($route, $parameters = array())
    {
        try {
            return $this->urlGenerator->generate($route, $parameters);
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'orchestra_url';
    }
}
