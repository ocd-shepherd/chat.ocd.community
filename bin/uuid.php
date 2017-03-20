<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

echo Ramsey\Uuid\Uuid::uuid4()->toString() . "\n";
