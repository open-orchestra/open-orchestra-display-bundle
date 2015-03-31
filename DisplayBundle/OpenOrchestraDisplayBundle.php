<?php

namespace OpenOrchestra\DisplayBundle;

use OpenOrchestra\DisplayBundle\DependencyInjection\Compiler\DisplayBlockCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenOrchestraDisplayBundle
 */
class OpenOrchestraDisplayBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DisplayBlockCompilerPass());
    }
}
