<?php

namespace OpenOrchestra\DisplayBundle\BBcode;

use OpenOrchestra\BBcodeBundle\Definition\BBcodeDefinition;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNodeInterface;
use OpenOrchestra\BBcodeBundle\ElementNode\BBcodeElementNode;
use Symfony\Component\Templating\EngineInterface;
use OpenOrchestra\DisplayBundle\Manager\NodeManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use OpenOrchestra\DisplayBundle\Exception\NodeNotFoundException;

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
        }
        $parameters = json_decode(html_entity_decode($option['link']), true);
        $uri = '#';
        try {
            $linkName = $this->nodeManager->getRouteDocumentName($parameters);
            try {
                $routeCompileParameters = array();
                if (array_key_exists('contentSearch_contentId', $parameters)) {
                    $routeCompileParameters['contentId'] = $parameters['contentSearch_contentId'];
                }
                $uri = $this->urlGenerator->generate($linkName, $routeCompileParameters, UrlGeneratorInterface::ABSOLUTE_PATH).(array_key_exists('query', $parameters) ? $parameters['query'] : '');
            } catch(RouteNotFoundException $e) {
            }
        } catch (NodeNotFoundException $e) {
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
