<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        $this->tag->prependTitle('phalcon-web ');
        $this->view->setVar("controller", $this->dispatcher->getControllerName());
        $this->view->setVar("action", $this->dispatcher->getActionName());
    }

    protected function jsonView($msg, $data = null, $status = 200)
    {
        $this->view->disable();

        $this->response->setHeader("Content-Type", "application/json; charset=utf-8");
        $this->response->setStatusCode($status);
        $this->response->setJsonContent([
            'msg' => $msg,
            'data' => $data,
            'status' => $status,
        ]);
        $this->response->send();

        return true;
    }
}