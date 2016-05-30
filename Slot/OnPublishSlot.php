<?php

namespace Pvr\EzSocialBundle\Slot;

use eZ\Publish\Core\SignalSlot\Slot as BaseSlot;
use eZ\Publish\Core\SignalSlot\Signal;
use eZ\Publish\API\Repository\ContentService;
use Psr\Log\LoggerInterface;
use Pvr\EzSocialBundle\Networks\Handler\TwitterHandler;
use Pvr\EzSocialBundle\Networks\NetworkInterface;

class OnPublishSlot extends BaseSlot
{
    /**
     * @var \eZ\Publish\API\Repository\ContentService
     */
    private $contentService;
    private $twitterService;
    private $logger;

    public function __construct( ContentService $contentService, LoggerInterface $logger, NetworkInterface $twitterHandler )
    {
        $this->contentService = $contentService;
        $this->logger = $logger;
        $this->twitterService = $twitterHandler;
    }

    /**
     * Receive the given $signal and react on it.
     *
     * @param Signal $signal
     */
    public function receive(Signal $signal)
    {
        if ( !$signal instanceof Signal\ContentService\PublishVersionSignal) {
            return;
        }

        // Load content
        $content = $this->contentService->loadContent( $signal->contentId, null, $signal->versionNo );
        $title = $content->getField('title')->value;

        // Send to the service
        $this->logger->debug('Twitter Publish Signal: ' . print_r($title, true) );
        $this->twitterService->publish(['status' => $title->text]);
    }
}