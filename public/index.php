<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$container = require 'config/container.php';

//$handler = new Predis\Session\Handler(
//    $container->get('redis'),
//    ['gc_maxlifetime' => 86400 * 180]
//);
//session_set_save_handler($handler);

$app = $container->get(Zend\Expressive\Application::class);

$app->run();
