<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RakitanTest extends TestCase
{
    protected static $app;

    public static function setUpBeforeClass()
    {
        $injector = include_once __DIR__.'/dependency.php';
        self::$app = $injector->make('Application');
    }

    public function testHomepage()
    {
        $request = Request::create('http://test.com/rakitan/');
        $response = self::$app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @dataProvider providerTestDefaultRoute
     */
    public function testDefaultRoute($route, $expectedStatusCode)
    {
        $request = Request::create($route);
        $response = self::$app->handle($request);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    public function providerTestDefaultRoute()
    {
        return [
            ['http://test.com/rakitan/home', 200],
            ['http://test.com/rakitan/home/', 200],
            ['http://test.com/rakitan/home/index', 200],
            ['http://test.com/rakitan/home/test', 404],
            ['http://test.com/rakitan/home/test/', 404],
        ];
    }

    /**
     * @dataProvider providerTestDefaultModuleRoute
     */
    public function testDefaultModuleRoute($route, $expectedStatusCode)
    {
        $request = Request::create($route);
        $response = self::$app->handle($request);
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
    }

    public function providerTestDefaultModuleRoute()
    {
        return [
            ['http://test.com/rakitan/module/example/index', 200],
            ['http://test.com/rakitan/module/example/index/', 404],
            ['http://test.com/rakitan/module/example/notfound', 404],
        ];
    }
}
