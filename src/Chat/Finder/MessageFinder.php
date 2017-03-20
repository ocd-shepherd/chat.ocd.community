<?php
namespace Chat\Finder;

use Predis\Client;

class MessageFinder
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function chatMessages($roomId, $limit = 50, $max = '+inf')
    {
        $messageKeys = $this->redis->zrevrangebyscore('chat:messages:'.$roomId, $max, '-inf', 'LIMIT', 0, $limit);

        $messages = [];
        foreach ($messageKeys as $messageKey) {
            if (strpos($messageKey, 'gliph') !== false) {
                $message = $this->processGliphMessage($messageKey);
            } else {
                $message = $this->processMessage($messageKey);
            }
            $messages[] = $message;
        }
        return $messages;
    }

    public function latestMessages($roomId, $limit = 50)
    {
        $messageKeys = $this->redis->zrange('chat:messages:'.$roomId, '-'.$limit, '-1');

        $messages = [];
        foreach ($messageKeys as $messageKey) {
            if (strpos($messageKey, 'gliph') !== false) {
                $message = $this->processGliphMessage($messageKey);
            } else {
                $message = $this->processMessage($messageKey);
            }
            $messages[] = $message;
        }
        return $messages;
    }

    private function processMessage($messageKey)
    {
        $message = $this->redis->hgetall($messageKey);

        if (!isset($message['sender'])) {
            return false; // TODO how/why does this happen? development quirk?
        }

        $sender = $this->redis->hgetall('user:'.$message['sender']);

        $senderName = $sender['username'];

        $response = [
            'source' => 'native',
            'id' => $message['id'],
            'key' => $messageKey,
            'senderName' => $senderName,
            'senderAvatar' => $sender['avatar'] ?? '/no-avatar.png',
            'message' => $message['message'],
            'timestamp' => $message['timestamp'],
        ];

        if (isset($message['media']) && $message['media']) {
            $media = explode('#', $message['media']);
            foreach ($media as $item) {
                $item = explode('|', $item);
                $response['media'][] = [
                    'thumbnail' => $item[0],
                    'fullsize' => $item[1] ?? false,
                ];
            }
        }

        return $response;

    }

    private function processGliphMessage($messageKey)
    {
        $message = $this->redis->hgetall($messageKey);
        $sender = $this->redis->hgetall('gliph:user:'.$message['sender']);

        $senderName = $sender['facet:First Name'] ?? $sender['facet:Pseudonym'] ?? $sender['facet:Username'];

        $response = [
            'source' => 'gliph',
            'key' => $messageKey,
            'id' => $message['id'],
            'senderName' => $senderName,
            'senderAvatar' => '/gliph-media/' . ($sender['photo:thumbnail'] ?? 'nopic') . '.png',
            'message' => $message['text'],
            'timestamp' => $message['timestamp'],
        ];

        if (isset($message['media'])) {
            $response['media'] = [];

            $media = explode('#', $message['media']);
            foreach ($media as $item) {
                $item = explode('|', $item);
                $response['media'][] = [
                    'thumbnail' => '/gliph-media/' . $item[0] . '.png',
                    'fullsize' => '/gliph-media/' . $item[1] . '.png',
                ];
            }
        }

        return $response;
    }




}
