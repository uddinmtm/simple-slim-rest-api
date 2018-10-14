<?php
// Application middleware

$app = new \Slim\App($container);
$app->add(new \Slim\HttpCache\Cache('public', 86400));
// e.g: $app->add(new \Slim\Csrf\Guard);
