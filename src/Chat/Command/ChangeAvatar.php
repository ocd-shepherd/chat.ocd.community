<?php
namespace Chat\Command;

use EvantSource\ReadOnlyProperties;

class ChangeAvatar
{
    use ReadOnlyProperties;

    private $userId;

    private $mediaPath;
}
