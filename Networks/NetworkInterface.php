<?php

namespace Pvr\EzSocialBundle\Networks;

interface NetworkInterface
{
    public function connect();
    public function publish($parameters);
}