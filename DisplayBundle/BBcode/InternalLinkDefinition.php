<?php

namespace OpenOrchestra\DisplayBundle\BBcode;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode;
use Symfony\Component\Templating\EngineInterface;
use OpenOrchestra\DisplayBundle\Manager\NodeManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\RequestContext;

/**
 * Class InternalLinkDefinition
 */
class InternalLinkDefinition extends BBcodeDefinition
{

    protected $urlGenerator;
    protected $nodeManager;
    protected $templating;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param NodeManager           $nodeManager
     * @param EngineInterface       $templating
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        NodeManager $nodeManager,
        EngineInterface $templating
    ){
        parent::__construct('link', '');

        $this->urlGenerator = $urlGenerator;
        $this->nodeManager = $nodeManager;
        $this->templating = $templating;
        $this->useOption = true;
    }

    /**
     * Returns this node as HTML
     *
     * @return string
     */
    public function getHtml(BBcodeElementNode $el)
    {
        return $this->generateHtml($el);
    }

    /**
     * Returns this node as HTML, in a preview context
     *
     * @return string
     */
    public function getPreviewHtml(BBcodeElementNodeInterface $el)
    {
        return $this->generateHtml($el, true);
    }

    /**
     * @param BBcodeElementNodeInterface $el
     * @param bool                       $preview
     */
    protected function generateHtml(BBcodeElementNodeInterface $el, $preview = false)
    {
        $children = $el->getChildren();
        $option = $el->getAttribute();
        if ($preview) {
            return $this->templating->render(
                'OpenOrchestraDisplayBundle::BBcode/link.html.twig',
                array(
                    'href' => '#',
                    'options' => html_entity_decode($option['link']),
                    'text' => $children[0]->getAsBBCode(),
                )
            );
        } else {
            $parameters = json_decode(html_entity_decode($option['link']), true);
            $linkName = $this->nodeManager->getNodeRouteNameWithParameters($parameters);

            $parameterBag = new ParameterBag($parameters);

            $query = array();
            if (array_key_exists('query', $parameters)) {
                $query = array($parameters['query']);
            }

            $uri = '#';
            foreach ($this->urlGenerator->all() as $router) {
                $generator = $router->getGenerator();
                if(method_exists($generator, 'generateWithParameter')){
                    $uri = $generator->generateWithParameter($parameterBag, $linkName, $query, UrlGeneratorInterface::ABSOLUTE_PATH);
                }
            }
            return $this->templating->render(
                'OpenOrchestraDisplayBundle::BBcode/link.html.twig',
                array(
                    'href' => $uri,
                    'options' => '',
                    'text' => $children[0]->getAsBBCode(),
                )
            );
        }
    }
}
