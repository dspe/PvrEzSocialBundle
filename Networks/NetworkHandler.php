<?php

namespace Pvr\EzSocialBundle\Networks;

use Symfony\Component\DependencyInjection\ContainerInterface;

class NetworkHandler
{
    private $container;
    private $networks;

    public function __construct(ContainerInterface $container)
    {
        $this->networks = [];
        $this->container = $container;
    }

    /**
     * Add network
     *
     * @param NetworkInterface $network
     * @param $alias
     */
    public function addNetwork(NetworkInterface $network, $alias)
    {
        $this->networks[$alias] = $network;
    }

    /**
     * Check if the network exists
     *
     * @param $alias
     * @return bool
     */
    public function has($alias) {
        if (array_key_exists($alias, $this->networks)) {
            return true;
        }
        return false;
    }

    /**
     * @param $alias
     * @return null|object
     */
    public function get($alias) {
        if ($this->container->has('pvr_ezsocial.' . $alias . '.handler') && array_key_exists($alias, $this->networks)) {
            return $this->container->get('pvr_ezsocial.' . $alias . '.handler');
        }
        return null;
    }
}