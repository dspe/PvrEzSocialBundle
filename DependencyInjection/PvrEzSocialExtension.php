<?php

namespace Pvr\EzSocialBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PvrEzSocialExtension extends Extension
{
    public function getAlias()
    {
        return 'pvr_ezsocial';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('pvr_ezsocial.networks.twitter.consumer_key', $config['networks']['twitter']['consumer_key']);
        $container->setParameter('pvr_ezsocial.networks.twitter.consumer_secret', $config['networks']['twitter']['consumer_secret']);
        $container->setParameter('pvr_ezsocial.networks.twitter.access_token', $config['networks']['twitter']['access_token']);
        $container->setParameter('pvr_ezsocial.networks.twitter.access_secret', $config['networks']['twitter']['access_secret']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}