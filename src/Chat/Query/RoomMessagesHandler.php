<?php
namespace Chat\Query;

use Chat\Finder\MessageFinder;

final class RoomMessagesHandler
{
    private $finder;

    public function __construct(MessageFinder $finder)
    {
        $this->finder = $finder;
    }

    public function __invoke(RoomMessages $query)
    {
        return $this->finder->chatMessages($query->roomId, $query->limit, $query->before);
        //return $this->finder->latestMessages($query->roomId);
    }
}
