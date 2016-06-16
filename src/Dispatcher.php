<?php

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * route dispatcher
 * 
 * @author Arif Kurniawan <arifk97@gmail.com>
 */
class Dispatcher
{
    /**
     * @var \Auryn\Injector
     */
    public $injector;

    /**
     * 
     * @param string $target Target controller, format: Class#method
     * @param string $params method parameters
     * @return Symfony\Component\HttpFoundation\Response Response object
     */
    public function dispatch($target, $params)
    {
        return call_user_func_array($this->createController($target), $params);
    }

    /**
     * resolve router target to class object
     * @param string $target route target, format: Class#method or Class/method
     * @return array [object, method]
     */
    public function createController($target)
    {
        $matches = null;
        if (false === preg_match('/[#|\/]/', $target, $matches)) {
            throw new HttpException(404, 'Invalid route: '.$target);
        }
        list($class, $method) = explode($matches[0], $target, 2);
        $class = 'Controller\\'.$class;
        if (!class_exists($class)) {
            throw new HttpException(404, sprintf('Controller "%s" does not exist.', $class));
        }
        $instance = $this->injector->make($class);
        if (!method_exists($instance, $method)) {
            throw new HttpException(404, sprintf('Action "%s" not found on controller "%s"', $method, $class));
        }
        return [$instance, $method];
    }
}
