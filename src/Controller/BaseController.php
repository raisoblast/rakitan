<?php

namespace Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Template\Renderer;

/**
 * base controller
 */
abstract class BaseController
{
    /**
     *
     * @var Request
     */
    protected $request;

    /**
     *
     * @var Response
     */
    protected $response;

    /**
     *
     * @var Renderer
     */
    protected $view;

    /**
     *
     * @var array
     */
    protected $config;

    /**
     *
     * @var \Dispatcher
     */
    protected $dispatcher;

    /**
     *
     * @var Session
     */
    protected $session;

    /**
     *
     * @var \Lib\MyFlash
     */
    protected $flash;

    public function __construct(
            Request $request,
            Response $response,
            Renderer $view,
            \Dispatcher $dispatcher,
            Session $session,
            array $config)
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
        $this->config = $config;
        $this->dispatcher = $dispatcher;
        $this->session = $session;
        $this->flash = $session->getFlashBag();
        $this->view->engine->addData([
            'title' => 'Rakitan Framework',
            'flash' => $this->flash,
            'module' => $this->request->get('_module'),
            'controller' => $this->request->get('_controller'),
            'action' => $this->request->get('_action'),
        ]);
    }

    /**
     * Redirect to url
     * @param string $url format seperti di route.php, e.g. /katalog
     * @param array $cookies optional, cookies to be added to next request
     * @return RedirectResponse
     */
    public function redirect($url, $cookies=[])
    {
        $response = new RedirectResponse($this->config['baseDir'].$url);
        foreach ($cookies as $cookie) {
            if (!$cookie instanceof Cookie) {
                throw new \InvalidArgumentException('Parameter is not a valid Cookie object.');
            }
            $response->headers->setCookie($cookie);
        }
        return $response;
    }

    public function forbidden()
    {
        $response = $this->forward('Home#forbidden');
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        return $response;
    }

    /**
     * Forward to another controller
     * @param string $target e.g. Class#method
     * @param array $params
     * @return Symfony\Component\HttpFoundation\Response Response object
     */
    public function forward($target, $params=[])
    {
        return $this->dispatcher->dispatch($target, $params);
    }

    public function url($path=null)
    {
        return $this->config['baseDir'].$path;
    }
}
