<?php

namespace OpenOrchestra\BBcodeBundle\DependencyInjection;

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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_orchestra_bbcode');

        $rootNode->children()
            ->arrayNode('validators')
                ->info('Array of validators')
                ->useAttributeAsKey('validator_name')
                ->prototype('scalar')->end()
            ->end()

            ->arrayNode('code_definitions')
                ->info('Array of tag definitions')
                ->useAttributeAsKey('tag_name')
                ->prototype('array')->children()
                    ->scalarNode('tag')->isRequired()->end()
                    ->scalarNode('html')->isRequired()->end()
                    ->arrayNode('parameters')
                        ->prototype('array')->children()
                            ->booleanNode('use_option')->end()
                            ->booleanNode('parse_content')->end()
                            ->scalarNode('body_validator')->end()
                            ->scalarNode('option_validator')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
