<?php

use Phalcon\DI;

class DispatchEvents
{
    public function __construct()
    {
        $this->di = DI::getDefault();
    }

    public function beforeDispatchLoop($event, $dispatcher)
    {
        return true;
    }

    public function beforeDispatch($event, $dispatcher)
    {
        //$this->guard($dispatcher);

        return true;
    }

    public function beforeExecuteRoute($event, $dispatcher)
    {
        return true;
    }

    public function afterExecuteRoute($event, $dispatcher)
    {
        return true;
    }

    public function beforeNotFoundAction($event, $dispatcher)
    {
        return true;
    }

    public function beforeException($event, $dispatcher)
    {
        return true;
    }

    public function afterDispatch($event, $dispatcher)
    {
        return true;
    }

    public function afterDispatchLoop($event, $dispatcher)
    {
        return true;
    }

    // 门卫
    private function guard($dispatcher)
    {
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $user = UserModel::fromSession();
        $accessible = PrivilegeModel::check($controller, $action, $user['role']['id']);

        if (!$accessible) {
            if ($user['role']['name'] == 'guest') {
                throw new AuthException('您还没有登录', 401);
            } else {
                throw new AuthException('没有权限', 403);
            }
        }

        return $this;
    }
}