<?php

namespace OpenOrchestra\DisplayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_orchestra_display');

        $rootNode->children()
            ->scalarNode('administrator_email')->defaultValue('nicolas.thal@businessdecison.com')->end()
            ->scalarNode('contact_signature')->defaultValue('Orchestra')->end()
        ->end();

        return $treeBuilder;
    }
}
