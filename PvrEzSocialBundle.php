<?php

namespace Pvr\EzSocialBundle;

use Pvr\EzSocialBundle\DependencyInjection\PvrEzSocialExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PvrEzSocialBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new PvrEzSocialExtension();
        }

        return $this->extension;
    }
}
