<?php

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The Rakitan Framework
 * 
 * @author Arif Kurniawan <arifk97@gmail.com>
 */
class Application implements HttpKernelInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var AltoRouter
     */
    protected $router;

    /**
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     *
     * @var array|boolean
     */
    protected $routeMatch;

    public function __construct(\AltoRouter $router,
            Dispatcher $dispatcher,
            array $config=[])
    {
        $this->config = $config;
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     *
     * @param Request $request A Request instance
     * @param int     $type    The type of the request
     *                         (one of HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST)
     * @param bool    $catch   Whether to catch exceptions or not
     *
     * @return Response A Response instance
     *
     * @throws \Exception When an Exception occurs during processing
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        try {
            $match = $this->routeMatch;
            if (!$match) {
                $match = $this->router->match($request->getPathInfo());
            }
            if ($match) {
                list($module, $controller, $action) = $this->processRoute($match);
                $request->attributes->add([
                    '_module' => $module,
                    '_controller' => $controller,
                    '_action' => $action,
                ]);
                $response = $this->dispatcher->dispatch($match['target'], $match['params']);
            } else {
                $response = $this->dispatcher->dispatch('Home#error', [
                    'message' => 'Halaman tidak ditemukan: '.$request->getPathInfo()
                ]);
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            if (!$catch) {
                throw $e;
            }
            $response = $this->dispatcher->dispatch('Home#error', [
                'message' => '['.$e->getCode().'] '.$e->getMessage()
            ]);
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        //$response->setMaxAge(300);
        return $response;
    }

    /**
     * Set matched route from middleware
     * @param array|boolean $match
     */
    public function setRouteMatch($match)
    {
        $this->routeMatch = $match;
    }

    /**
     * Process default route when no route matched
     * try default route: controller/action then module/controller/action
     * @param array $match
     * @return array array contains module, controller, action matched
     */
    private function processRoute(&$match)
    {
        $module = '';
        $controller = '';
        $action = '';
        if ($match['name'] == 'default-route') {
            $controller = $match['params']['controller'];
            $action = $match['params']['action'];
            $match['target'] = ucfirst($controller).'#'.$action;
            $match['params'] = []; // kosongi agar tidak diproses di controller
        } elseif ($match['name'] == 'default-module-route') {
            $module = $match['params']['module'];
            $controller = $match['params']['controller'];
            $action = $match['params']['action'];
            $match['target'] = ucfirst($module).'\\'.ucfirst($controller).'#'.$action;
            $match['params'] = []; // kosongi agar tidak diproses di controller
        } else {
            list($controller, $action) = explode('#', $match['target']);
            $controller = strtolower($controller);
            if (strpos($controller, '\\') !== false) {
                list($module, $controller) = explode('\\', $controller, 2);
            }
        }
        return [$module, $controller, $action];
    }
}
