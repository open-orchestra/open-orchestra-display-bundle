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
        $this->loadValidators($container);
        $this->loadDefinitions($container);
    }

    /**
     * Load into the parser the validators described:
     * - in the container configuration (parameters files)
     * - with tagged services (open_orchestra_bbcode.validators)
     * 
     * @param ContainerBuilder $container
     */
    protected function loadValidators(ContainerBuilder $container)
    {
        $parser = $container->getDefinition('open_orchestra_bbcode.bbcode_parser');

        if ($container->hasParameter('open_orchestra_bbcode.validators')) {
            $validators = $container->getParameter('open_orchestra_bbcode.validators');
            $parser->addMethodCall('loadValidatorsFromConfiguration', $validators);
        }

        $this->addStrategyToManager($container, 'open_orchestra_bbcode.bbcode_parser', 'open_orchestra_bbcode.validator', 'loadValidatorFromService');
    }

    /**
     * Load into the parser the definitions described:
     * - in the container configuration (parameters files)
     * - with tagged services (open_orchestra_bbcode.code_definitions)
     * 
     * @param ContainerBuilder $container
     */
    protected function loadDefinitions(ContainerBuilder $container)
    {
        $parser = $container->getDefinition('open_orchestra_bbcode.bbcode_parser');

        if ($container->hasParameter('open_orchestra_bbcode.code_definitions')) {
            $definitions = $container->getParameter('open_orchestra_bbcode.code_definitions');
            $parser->addMethodCall('loadDefinitionsFromConfiguration', $definitions);
        }

        $this->addStrategyToManager($container, 'open_orchestra_bbcode.bbcode_parser', 'open_orchestra_bbcode.code_definition', 'loadDefinitionFromService');
    }
}
