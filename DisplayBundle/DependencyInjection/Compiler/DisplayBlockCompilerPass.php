<?php

namespace PHPOrchestra\DisplayBundle\DependencyInjection\Compiler;

use PHPOrchestra\BaseBundle\DependencyInjection\Compiler\AbstractTaggedCompiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DisplayBlockCompilerPass
 */
class DisplayBlockCompilerPass extends AbstractTaggedCompiler implements CompilerPassInterface
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
        $managerName = 'php_orchestra_display.display_block_manager';
        $tagName = 'php_orchestra_display.display_block.strategy';

        $this->addStrategyToManager($container, $managerName, $tagName);
    }
}
