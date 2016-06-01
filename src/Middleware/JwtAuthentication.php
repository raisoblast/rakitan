<?php

namespace Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * JSON Web Token authentication middleware
 * @author Arif Kurniawan <arifk97@gmail.com>
 */
class JwtAuthentication implements HttpKernelInterface
{
    /**
     *
     * @var HttpKernelInterface
     */
    private $app;

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
     * @var array
     */
    protected $config;

    /**
     *
     * @var \Lib\MyAuth
     */
    protected $auth;

    /**
     *
     * @var \Lib\MyFlash
     */
    protected $flash;

    public function __construct(HttpKernelInterface $app,
            \AltoRouter $router,
            \Lib\MyAuth $auth,
            \Dispatcher $dispatcher,
            Session $session,
            array $config=[])
    {
        $this->app = $app;
        $this->router = $router;
        $this->config = $config;
        $this->auth = $auth;
        $this->dispatcher = $dispatcher;
        $this->flash = $session->getFlashBag();
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $match = $this->router->match($request->getPathInfo());
        $route = substr($request->getPathInfo(), strlen(rtrim($this->config['baseDir'], '/')));
        if ($match) {
            $tokenValid = false;
            $jwtCookie = $this->config['jwt']['cookieName'];
            $jwtKey = $this->config['jwt']['key'];
            // check token from cookie
            if ($request->cookies->has($jwtCookie)) {
                $jwt = $request->cookies->get($jwtCookie);
                try {
                    $decoded = JWT::decode($jwt, $jwtKey, ['HS256']);
                    if ($decoded->e > time()) {
                        $tokenValid = true;
                        $this->auth->init($decoded->uid);
                    }
                } catch (\Exception $e) {
                    $tokenValid = false;
                    if (!$catch) {
                        throw $e;
                    }
                    $response = $this->dispatcher->dispatch('Home#error', [
                        'message' => '['.$e->getCode().'] '.$e->getMessage().
                        '<pre>'.$e->getTraceAsString().'</pre>'
                    ]);
                    $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                    return $response;
                }
            }
            $allowed = false;
            $isPublic = false;
            foreach ($this->config['publicArea'] as $publicRoute) {
                if (preg_match('/^'.addcslashes($publicRoute, '/').'/', $route)) {
                    $isPublic = true;
                    break;
                }
            }
            if ($match['name'] == 'home') {
                $isPublic = true;
            }
            if ($isPublic) {
                if ($route == '/login' && $tokenValid) {
                    return new RedirectResponse($this->router->generate('dashboard'));
                }
                $allowed = true;
            } else {
                $allowed = $tokenValid;
            }
            if ($allowed) {
                $this->app->setRouteMatch($match);
                return $this->app->handle($request, $type, $catch);
            } else {
                $this->flash->warning('Sesi Anda telah habis atau Anda tidak berhak mengakses halaman ini, silakan login terlebih dahulu!');
                $response = $this->dispatcher->dispatch('User#login', []);
                $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
                return $response;
            }
        }
        $response = $this->dispatcher->dispatch('Home#error', [
            'message' => 'Halaman tidak ditemukan: '.$route
        ]);
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;
    }

}
