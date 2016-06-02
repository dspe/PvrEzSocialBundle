<?php

namespace Pvr\EzSocialBundle;

use Pvr\EzSocialBundle\DependencyInjection\Compiler\NetworkCompilerPass;
use Pvr\EzSocialBundle\DependencyInjection\PvrEzSocialExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PvrEzSocialBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new NetworkCompilerPass());
    }

    /**
     * Customize bundle's alias
     *
     * @return PvrEzSocialExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new PvrEzSocialExtension();
        }

        return $this->extension;
    }
}
