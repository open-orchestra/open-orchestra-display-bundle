<?php

namespace OpenOrchestra\BBcodeBundle\DependencyInjection\Compiler;

use OpenOrchestra\BaseBundle\DependencyInjection\Compiler\AbstractTaggedCompiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class BBcodeCompilerPass
 */
class BBcodeCompilerPass extends AbstractTaggedCompiler implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $this->loadElements('validators', $container, 'loadValidatorsFromConfiguration', 'loadValidatorsFromService');
        $this->loadElements('code_definitions', $container, 'loadDefinitionsFromConfiguration', 'loadDefinitionsFromService');
    }

    /**
     * Load validators or code definitions from both configuration and tagged services
     * 
     * @param string           $tagName
     * @param ContainerBuilder $container
     * @param string           $methodForConfiguration
     * @param string           $methodForService
     */
    protected function loadElements($tagName, ContainerBuilder $container, $methodForConfiguration, $methodForService)
    {
        $parserName = 'open_orchestra_bbcode.bbcode_parser';
        $tagName = 'open_orchestra_bbcode.' . $tagName;

        if ($container->hasParameter($tagName)) {
            $parser = $container->getDefinition($parserName);
            $elements = $container->getParameter($tagName);
            $parser->addMethodCall($methodForConfiguration, $elements);
        }

        $this->addStrategyToManager($container, $parserName, $tagName, $methodForService);
    }
}
