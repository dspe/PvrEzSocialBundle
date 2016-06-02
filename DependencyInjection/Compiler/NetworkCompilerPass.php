<?php

namespace Pvr\EzSocialBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NetworkCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('pvr_ezsocial.networks')) {
            return;
        }

        $definition = $container->findDefinition('pvr_ezsocial.networks');
        $taggedService = $container->findTaggedServiceIds('pvr_ezsocial.network');

        foreach ($taggedService as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addNetwork',
                    [new Reference($id), $attributes["alias"]]
                );
            }
        }
    }
}