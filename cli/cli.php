<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;

// autoload tasks
spl_autoload_register(function($file) {
    $file = str_replace('\\', '/', $file) . '.php';
    include $file;
});

/* @var $injector \Auryn\Injector */
$injector = include('dependency.php');

$app = new Application;
$app->add($injector->make('Task\HelloWorld'));
$app->run();
