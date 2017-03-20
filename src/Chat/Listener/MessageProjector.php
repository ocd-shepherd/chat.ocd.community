<?php
declare(strict_types=1);

namespace Chat\Listener;

use Predis\Client;
use Chat\Command\SendMessage as MessageSent;
use Auth\Finder\UserFinder;

class MessageProjector
{
    private $redis;

    private $userFinder;

    public function __construct(Client $redis, UserFinder $userFinder)
    {
        $this->redis      = $redis;
        $this->userFinder = $userFinder;
    }

    public function __invoke($event)
    {
        switch(get_class($event)) {
            case MessageSent::class:
                return $this->onMessageSent($event);
                break;
        }
    }

    public function onMessageSent(MessageSent $e)
    {
        $now = microtime(true);

        $rKey = 'chat:messages:' . $e->roomId;
        $this->redis->zAdd($rKey, $now, 'message:'.$e->messageId);

        $rKey = 'message:' . $e->messageId;
        $data = [
            'id'        => $e->messageId,
            'sender'    => $e->userId,
            'roomId'    => $e->roomId,
            'message'   => $e->message,
            'timestamp' => $now,
        ];

        if (is_array($e->media)) {
            $mediaFiles = array_map(function($media) {
                return implode('|', $media);
            }, $e->media);

            $data['media'] = implode('#', $mediaFiles);
        }

        $this->redis->hMSet($rKey, $data);

        $this->redis->publish('new-message', $e->roomId);

        //$username = $this->userFinder->findUsernameByUserId($e->userId);
        //$gliphMsg = "{$username} says:\n\n{$e->message}";

        //if (is_array($e->media)) {
        //    $gliphMsg .= "\n\n";
        //    foreach ($e->media as $media) {
        //        $gliphMsg .= "https://chat.ocd.community/uploads/default/{$media}\n";

        //    }
        //}

        //$rKey = 'message:28969b6b-002b-4379-bf49-35570802c423';
        //$this->redis->publish('message-to-gliph-' . $e->roomId, $rKey);

        return $e->messageId;
    }
}
