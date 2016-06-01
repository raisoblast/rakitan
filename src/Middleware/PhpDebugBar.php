<?php

namespace Middleware;

use DebugBar\JavascriptRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * PHP Debugbar (maximebf/php-debugbar) middleware
 * @author Arif Kurniawan <arifk97@gmail.com>
 */
class PhpDebugBar implements HttpKernelInterface
{
    /**
     *
     * @var type HttpKernelInterface
     */
    protected $app;

    /**
     *
     * @var type JavascriptRenderer
     */
    protected $debugbarRenderer;

    /**
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    public function __construct(HttpKernelInterface $app,
            JavascriptRenderer $debugbarRenderer,
            \Dispatcher $dispatcher)
    {
        $this->app = $app;
        $this->debugbarRenderer = $debugbarRenderer;
        $this->dispatcher = $dispatcher;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        try {
            $response = $this->app->handle($request, $type, $catch);
            $responseBody = $response->getContent();
            $debugbarHead = $this->debugbarRenderer->renderHead();
            $debugbarBody = $this->debugbarRenderer->render();
            $response->setContent($responseBody . $debugbarHead . $debugbarBody);
            return $response;
        } catch (\Exception $e) {
            if (!$catch) {
                throw $e;
            }
            $response = $this->dispatcher->dispatch('Home#error', [
                'message' => '['.$e->getCode().'] '.$e->getMessage()
            ]);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $response;
        }
    }
}
