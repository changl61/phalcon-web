<?php

use Phalcon\DI\FactoryDefault as DI;

/**
 * 调试工具
 */
class Debug
{
    /**
     * 打印
     * @param $val mixed
     */
    public static function printR($val)
    {
        header("Content-type:text/html; charset=utf-8");
        echo '<pre>';
        print_r($val);
        exit;
    }

    /**
     * 打印类的属性和方法
     * @param $class object
     */
    public static function printClass($class)
    {
        echo '<pre>';
        print_r(get_class_vars(get_class($class)));
        print_r(get_class_methods($class));
        exit;
    }

    /**
     * 报告异常
     * @param $e Exception
     */
    public static function reportException($e)
    {
        $view = DI::getDefault()->get('view');
        $request = DI::getDefault()->get('request');
        $response = DI::getDefault()->get('response');
        
        $code = (int)$e->getCode();
        $msg  = $e->getMessage();

        // 系统错误
        if ($code < 200) {
            $code = 500;
            $msg = 'Server Error';
        }

        // 返回JSON
        if ($request->getHeader('HTTP_ACCEPT') == 'application/json') {
            $response->setHeader("Content-Type", "application/json");
            $response->setContent(json_encode([
                'msg' => $msg,
                'data' => null,
                'status' => $code,
            ]));
            $response->send();
        }

        // 返回HTML
        else {
            $view->render('error', 'show'.$code);
        }
    }
}