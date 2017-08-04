<?php
/**
 * Copyright (c) 2017, 杰利信息科技有限公司 dev.jelly-tec.com
 * 摘    要：test.php
 * 作    者：wangld
 * 修改日期：2017/6/19
 */

header('Content-Type:text/html;charset=UTF-8');
ini_set('display_errors', 'on');
error_reporting(E_ALL);
include_once dirname(__FILE__) . '/inc/config.php';
include_once dirname(__FILE__) . '/libs/xiaotu/autoload.php';

$server = new Swoole\Http\Server('dev.jelly-tec.com', 9502);
$server->set(array(
        'worker_num' => 1,
        'daemonize' => true
    ));
$server->on('Request', function ($request, $response)
{
    var_dump($request->get);
    var_dump($request->post);
    var_dump($request->cookie);
    var_dump($request->files);
    var_dump($request->header);
    var_dump($request->server);

    $response->cookie("User", "Swoole");
    $response->header("X-Server", "Swoole");
    $response->end("<h1>Hello Swoole!</h1>" . var_export(array(
            $request->get,
            $request->post,
            $request->cookie,
            $request->files,
            $request->header,
            $request->server
        )));
});

$server->start();
