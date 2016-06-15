<?php

use Symfony\Component\HttpKernel\HttpKernelInterface;

require_once __DIR__.'/../vendor/autoload.php';

/* @var $injector \Auryn\Injector */
$injector = include(__DIR__.'/../src/dependency.php');

$request = $injector->make('Symfony\Component\HttpFoundation\Request');

/* @var $app Application */

if ('prod' == $config['environment']) {
    $app = $injector->make('Application'); // app without middleware
    $response = $app->handle($request);
} elseif ('dev' == $config['environment']) {
    $injector->define('Middleware\PhpDebugBar', ['app' => 'Application']);
    $injector->define('Middleware\Whoops', ['app' => 'Middleware\PhpDebugBar']);
    $app = $injector->make('Middleware\Whoops');
    $response = $app->handle($request, HttpKernelInterface::MASTER_REQUEST, false);
} elseif ('test' == $config['environment']) {
    $app = $injector->make('Application'); // app without middleware
    $response = $app->handle($request);
}

$response->send();
