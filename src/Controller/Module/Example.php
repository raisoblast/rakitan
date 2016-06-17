<?php

namespace Controller\Module;

use Controller\BaseController;

class Example extends BaseController
{
    public function index()
    {
        return $this->view->render('module/example/index');
    }
}
