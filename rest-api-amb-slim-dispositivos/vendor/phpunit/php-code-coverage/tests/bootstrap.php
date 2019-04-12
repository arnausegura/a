<?php
require str_replace("www/html", "docker-composer-lamp/www", __DIR__) . '/../vendor/autoload.php';
require str_replace("www/html", "docker-composer-lamp/www", __DIR__) . '/TestCase.php';

define('TEST_FILES_PATH', str_replace("www/html", "docker-composer-lamp/www", __DIR__) . '/_files/');
