<?php

namespace Pvr\EzSocialBundle\Slot;

use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\SignalSlot\Slot as BaseSlot;
use eZ\Publish\Core\SignalSlot\Signal;
use eZ\Publish\API\Repository\ContentService;
use Psr\Log\LoggerInterface;
use Pvr\EzSocialBundle\Networks\Handler\TwitterHandler;
use Pvr\EzSocialBundle\Networks\NetworkHandler;
use Pvr\EzSocialBundle\Networks\NetworkInterface;

class OnPublishSlot extends BaseSlot
{
    /**
     * @var \eZ\Publish\API\Repository\ContentService
     */
    private $contentService;
    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    private $contentTypeService;
    private $twitterService;
    private $networkHandler;
    private $logger;
    private $content_type;

    public function __construct(
        ContentService $contentService,
        ContentTypeService $contentTypeService,
        LoggerInterface $logger,
        NetworkInterface $twitterHandler,
        NetworkHandler $networkHandler,
        $content_type )
    {
        $this->contentService = $contentService;
        $this->logger = $logger;
        $this->twitterService = $twitterHandler;
        $this->networkHandler = $networkHandler;
        $this->content_type = $content_type;
        $this->contentTypeService = $contentTypeService;
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
        $contentTypeIdentifier = $this->contentTypeService->loadContentType( $content->contentInfo->contentTypeId )->identifier;

        $this->logger->debug('Prout: ' . print_r($this->content_type, true));
        $this->logger->debug('Prout2: ' . print_r($contentTypeIdentifier, true));

        if (isset($this->content_type[$contentTypeIdentifier])) {
            $networks = $this->content_type[$contentTypeIdentifier]['network'];
            // Check all networks
            foreach ($networks as $network) {
                // If network exist ...
                if ($this->networkHandler->has($network)) {
                    $status     = $content->getFieldValue($this->content_type[$contentTypeIdentifier]['status'])->text;
                    $siteaccess = $this->content_type[$contentTypeIdentifier]['siteaccess'];
                    $locationId = $content->contentInfo->mainLocationId;
                    // @TODO: image

                    $handler = $this->networkHandler->get($network);
                    if ($handler == null) {
                        // throw exception
                    }
                    $this->logger->debug(ucfirst($network) . ' Publish Signal');
                    $this->logger->info(print_r($status));
                    $handler->publish(['status' => $status, 'siteaccess' => $siteaccess, 'locationId' => $locationId]);
                }
            }
        }
    }
}