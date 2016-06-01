<?php

use Symfony\Component\HttpKernel\HttpKernelInterface;

require_once __DIR__.'/../vendor/autoload.php';

/* @var $injector \Auryn\Injector */
$injector = include(__DIR__.'/../src/dependency.php');

$request = $injector->make('Symfony\Component\HttpFoundation\Request');

/* @var $app Application */
//$app = $injector->make('Application'); // app without middleware

$injector->define('Middleware\PhpDebugBar', ['app' => 'Application']);
$app = $injector->make('Middleware\PhpDebugBar');

/* uncomment to enable JWT Auth middleware
$injector->define('Middleware\JwtAuthentication', ['app' => 'Application']);
$app = $injector->make('Middleware\JwtAuthentication');
 */

if ('prod' == $config['environment']) {
    $response = $app->handle($request);
} else {
    $response = $app->handle($request, HttpKernelInterface::MASTER_REQUEST, false);
}

$response->send();
