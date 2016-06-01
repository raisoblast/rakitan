<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

$config = include_once(__DIR__.'/../config/config.php');
$route = include_once(__DIR__.'/../config/route.php');

$baseDir = $config['baseDir'];

$injector = new Auryn\Injector;
// global parameter
$injector->defineParam('config', $config);

// route dispatcher
$injector->prepare('Dispatcher', function($obj, $injector) {
    $obj->injector = $injector;
});

// request
$request = Request::createFromGlobals();
$injector->share($request);

// router
$injector->define('AltoRouter', [':routes' => $route,
    ':basePath' => rtrim($baseDir, '/') // hapus trailing slash dari baseDir, agar dpt dibaca oleh altorouter
]);

// template engine
$injector->alias('Template\Renderer', 'Template\PlatesRenderer');
$injector->define('League\Plates\Engine', [
    ':directory' => __DIR__ . '/views/' . $config['theme']
]);
$injector->alias('League\Plates\Extension\ExtensionInterface', 'Template\MyPlatesExtension');
$injector->define('Template\MyPlatesExtension', [
    ':baseDir' => $baseDir
]);

// session and flash
$sessionFactory = function() {
    $session = new Session(null, null, new Lib\MyFlash);
    $session->setName('RakitanFramework');
    $session->start();
    return $session;
};
$injector->delegate('Symfony\Component\HttpFoundation\Session\Session', $sessionFactory);
$injector->share('Symfony\Component\HttpFoundation\Session\Session');

// authentication class
//$injector->share('Lib\MyAuth');

// middleware
$injector->alias('Debugbar\DebugBar', 'DebugBar\StandardDebugBar');
$injector->define('DebugBar\JavascriptRenderer', [':baseUrl' => $baseDir.'debugbar']);

return $injector;
