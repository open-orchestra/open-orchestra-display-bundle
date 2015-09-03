<?php

namespace OpenOrchestra\BBcodeBundle;

use OpenOrchestra\BBcodeBundle\DependencyInjection\Compiler\BBcodeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class OpenOrchestraBBcodeBundle
 *
 */
class OpenOrchestraBBcodeBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new BBcodeCompilerPass());
    }
}
