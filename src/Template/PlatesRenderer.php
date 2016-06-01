<?php

namespace Template;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlatesRenderer implements Renderer
{
    public $engine;

    public function __construct(Engine $engine, ExtensionInterface $extension)
    {
        $this->engine = $engine;
        $extension->register($this->engine);
    }

    public function render($template, $data = [], $status = 200)
    {
        $content = $this->engine->render($template, $data);
        return new Response($content, $status);
    }

    public function renderJson($data = [], $status = 200)
    {
        return new JsonResponse($data, $status);
    }

}
