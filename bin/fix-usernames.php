<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';
$container = require 'config/container.php';

$redis = $container->get('redis');

$usernames = $redis->hgetall('index:usernames');
var_dump($usernames);

foreach ($usernames as $username => $userId) {
    if ($username === strtolower($username)) continue;

    $redis->hset('index:usernames', strtolower($username), $userId);
    $redis->hdel('index:usernames', $username);
}

$usernames = $redis->hgetall('index:usernames');
var_dump($usernames);
