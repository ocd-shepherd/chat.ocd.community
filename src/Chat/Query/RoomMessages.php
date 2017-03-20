<?php
namespace Chat\Query;

use EvantSource\ReadOnlyProperties;

class RoomMessages
{
    use ReadOnlyProperties;

    private $roomId;

    private $before = '+inf'; // a little sloppy having redis logic exposed here?

    private $limit = 50;
}
