<?php

namespace Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Whoops (filp/whoops) middleware
 * @author Arif Kurniawan <arifk97@gmail.com>
 * modified from thecodingmachine/whoops-stackphp
 */
class Whoops implements HttpKernelInterface
{
    /**
     *
     * @var type HttpKernelInterface
     */
    protected $app;

    /**
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    public function __construct(HttpKernelInterface $app,
            \Dispatcher $dispatcher)
    {
        $this->app = $app;
        $this->dispatcher = $dispatcher;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
        if ($catch) {
            try {
                return $this->app->handle($request, $type, $catch);
            } catch (\Exception $e) {
                $method = \Whoops\Run::EXCEPTION_HANDLER;
                ob_start();
                $whoops->$method($e);
                $output = ob_get_clean();
                $code = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
                return new Response($output, $code);
            }
        } else {
            return $this->app->handle($request, $type, $catch);
        }
    }
}
