<?php

namespace Pvr\EzSocialBundle\Networks\Handler;

use Abraham\TwitterOAuth\TwitterOAuth;
use Psr\Log\LoggerInterface;
use Pvr\EzSocialBundle\Networks\NetworkInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TwitterHandler implements NetworkInterface
{
    /**
     * @var TwitterOAuth
     */
    private $connection;
    private $container;
    protected $consumer_key;
    protected $consumer_secret;
    protected $access_token;
    protected $access_secret;
    //protected $content_types;
    private $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $networks = $container->getParameter('pvr_ezsocial.networks');
        $this->consumer_key     = $networks['twitter']['consumer_key'];
        $this->consumer_secret  = $networks['twitter']['consumer_secret'];
        $this->access_token     = $networks['twitter']['access_token'];
        $this->access_secret    = $networks['twitter']['access_secret'];
       // $this->content_types    = $networks['twitter']['content_type'];
        $this->logger = $logger;
        $this->container = $container;
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

    /**
     * @param $parameters
     */
    public function publish($parameters)
    {
        if (!isset($parameters['status'])) {
            $this->logger->debug('Twitter: No status parameters');
            exit;
        }

        if (!isset($parameters['locationId'])) {
            $this->logger->debug('Twitter: No Location ID found');
            exit;
        }

        $location = $this->container->get('ezpublish.api.repository')
            ->getLocationService()->loadLocation($parameters['locationId']);

        $locationUrl = $this->container->get('router')->generate(
            $location,
            ['siteaccess' => $parameters['siteaccess']],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->connection->post("statuses/update", [
            "status" => $parameters['status'] . " " . $locationUrl
        ]);

        if ($this->connection->getLastHttpCode() != 200) {
            // Handle error case
            $this->logger->debug('Twitter: error connection: ' . $this->connection->getLastHttpCode() . ' ' . print_r($this->connection->getLastBody(), true));
        }
    }
}