<?php

namespace Pvr\EzSocialBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('pvr_ezsocial');

        $rootNode
            ->children()
            ->arrayNode('networks')
                ->children()
                    ->arrayNode('twitter')
                        ->children()
                            ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('consumer_secret')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('access_token')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('access_secret')->isRequired()->cannotBeEmpty()->end()
                            ->arrayNode('content_type')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
