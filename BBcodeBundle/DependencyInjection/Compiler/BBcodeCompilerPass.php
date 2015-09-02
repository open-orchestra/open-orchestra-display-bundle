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
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $managerName = 'open_orchestra_bbcode.configured_code_definition_set';

        $tagName = 'open_orchestra_bbcode.validator';
        $methodName = 'addValidator';
        $this->addStrategyToManager($container, $managerName, $tagName, $methodName);

        $tagName = 'open_orchestra_bbcode.code_definition';
        $methodName = 'addDefinition';
        $this->addStrategyToManager($container, $managerName, $tagName, $methodName);
    }
}
