<?php

use Phalcon\Mvc\Application;
use Phalcon\Events\Manager as EventsManager;

try {
    // --------------------------
    // 定义目录
    // --------------------------
    define('ROOT_PATH', realpath('..'));
    define('APP', 'web');


    // --------------------------
    // 启动引导
    // --------------------------
    require_once (ROOT_PATH.'/app/bootstrap.php');


    // --------------------------
    // 微应用实例
    // --------------------------
    $app = new Application();

    // 服务容器
    $app->setDI(require_once (ROOT_PATH.'/app/services.php'));

    // 输出内容
    echo $app->handle()->getContent();
}

catch (Exception $e) {
    Debug::reportException($e);
}