<?php

namespace Controller;

class Home extends BaseController
{
    public function index()
    {
        return $this->view->render('home/index');
    }

    public function error($message='')
    {
        return $this->view->render('home/error', ['message' => $message]);
    }
}
