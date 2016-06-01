<?php
namespace Lib;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class MyFlash extends FlashBag
{
    protected $cssClassMap = [
        'info'    => 'alert-info',
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'error'   => 'alert-danger',
    ];

    public function display()
    {
        foreach ($this->all() as $type => $messages) {
            foreach ($messages as $message) {
                echo '<div class="alert alert-dismissable '.$this->cssClassMap[$type].'">'.
                        '<button class="close" type="button" data-dismiss="alert" aria-hidden="true">&times;</button>'.
                        $message.'</div>';
            }
        }
    }

    public function info($message)
    {
        $this->add('info', $message);
    }

    public function success($message)
    {
        $this->add('success', $message);
    }

    public function warning($message)
    {
        $this->add('warning', $message);
    }

    public function error($message)
    {
        $this->add('error', $message);
    }
}
