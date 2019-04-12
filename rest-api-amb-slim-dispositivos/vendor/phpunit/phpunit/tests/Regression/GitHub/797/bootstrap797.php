<?php
// If process isolation fails to include this file, then
// PHPUnit_Framework_TestCase itself does not exist. :-)
require str_replace("www/html", "docker-composer-lamp/www", __DIR__) . '/../../../bootstrap.php';

const GITHUB_ISSUE = 797;
