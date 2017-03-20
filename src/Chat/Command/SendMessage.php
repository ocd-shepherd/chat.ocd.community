<?php
namespace Chat\Command;

use EvantSource\ReadOnlyProperties;

class SendMessage
{
    use ReadOnlyProperties;

    private $messageId;

    private $userId;

    private $roomId;

    private $message;

    private $media;
}
