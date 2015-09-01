<?php

namespace OpenOrchestra\BBcodeBundle\DependencyInjection\Compiler;

use OpenOrchestra\BaseBundle\DependencyInjection\Compiler\AbstractTaggedCompiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use OpenOrchestra\BBcodeBundle\Parser\ConfiguredCodeDefinitionSet;

/**
 * Class BBcodeCompilerPass
 */
class BBcodeCompilerPass extends AbstractTaggedCompiler implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $managerName = 'open_orchestra_bbcode.configured_code_definition_set';

        $tagName = ConfiguredCodeDefinitionSet::VALIDATOR_TAG_NAME;
        $methodName = 'addValidator';
        $this->addStrategyToManager($container, $managerName, $tagName, $methodName);

        $tagName = ConfiguredCodeDefinitionSet::CODE_DEFINITION_TAG_NAME;
        $methodName = 'addDefinition';
        $this->addStrategyToManager($container, $managerName, $tagName, $methodName);
    }
}
