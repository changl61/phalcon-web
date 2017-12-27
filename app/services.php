<?php

use Phalcon\DI\FactoryDefault as DI;
use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Session\Adapter\Files as SessionAdapter;

if (APP == 'web') {
    $di = new DI();
} else {
    $di = new CliDI();
}

// 配置项
$di->set('config', function() {
    $config = new ConfigIni(ROOT_PATH.'/app/config/prd.ini');
    if (is_readable(ROOT_PATH.'/app/config/dev.ini')) $config->merge(new ConfigIni(ROOT_PATH.'/app/config/dev.ini'));

    return $config;
});

// 调度器
$di->set('dispatcher', function() {
    $eventsManager = new EventsManager;
    $eventsManager->attach('dispatch', new DispatchEvents());

    $dispatcher = new Dispatcher;
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});


// 视图
$di->set('view', function() use ($di) {
    $view = new View();
    $view->setViewsDir(ROOT_PATH.'/app/views');

    // 模版引擎
    $view->registerEngines(['.volt' => function($view, $di) {
        $volt = new VoltEngine($view, $di);
        $volt->setOptions([
            "compiledPath" => ROOT_PATH.'/cache/views/',
            "compiledSeparator" => '>',
        ]);
        return $volt;
    }]);

    return $view;
});

// 数据库
$di->set('db', function() use ($di) {
    $config = $di->get('config')->get('database')->toArray();

    $dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
    unset($config['adapter']);

    return new $dbClass($config);
});

// 会话
$di->set('session', function() use ($di) {
    $session = new SessionAdapter();
    session_name('_token');
    session_set_cookie_params(60*60*12);
    session_save_path(ROOT_PATH.'/cache/session/');
    $session->start();

    return $session;
});

return $di;