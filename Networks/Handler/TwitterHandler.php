<?php

namespace Pvr\EzSocialBundle\Networks\Handler;

use Abraham\TwitterOAuth\TwitterOAuth;
use Psr\Log\LoggerInterface;
use Pvr\EzSocialBundle\Networks\NetworkInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwitterHandler implements NetworkInterface
{
    /**
     * @var TwitterOAuth
     */
    private $connection;
    protected $consumer_key;
    protected $consumer_secret;
    protected $access_token;
    protected $access_secret;
    private $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->consumer_key     = $container->getParameter('pvr_ezsocial.networks.twitter.consumer_key');
        $this->consumer_secret  = $container->getParameter('pvr_ezsocial.networks.twitter.consumer_secret');
        $this->access_token     = $container->getParameter('pvr_ezsocial.networks.twitter.access_token');
        $this->access_secret    = $container->getParameter('pvr_ezsocial.networks.twitter.access_secret');
        $this->logger = $logger;
        $this->connect();
    }

    /**
     * Connect to Twitter Oauth API
     */
    public function connect()
    {
        if (empty($this->access_token) || empty($this->access_secret)) {
            // @TODO
        }
        // Create the connection
        $this->connection = new TwitterOAuth(
            $this->consumer_key,
            $this->consumer_secret,
            $this->access_token,
            $this->access_secret
        );
    }

    public function publish($parameters)
    {
        if (!isset($parameters['status'])) {
            $this->logger->debug('Twitter: No status parameters');
        }

        $this->connection->post("statuses/update", [
            "status" => $parameters['status']
        ]);

        if ($this->connection->getLastHttpCode() != 200) {
            // Handle error case
            $this->logger->debug('Twitter: error connection: ' . $this->connection->getLastHttpCode() . ' ' . print_r($this->connection->getLastBody(), true));
        }
    }
}