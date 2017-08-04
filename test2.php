<?php
/**
 * Copyright (c) 2017, 杰利信息科技有限公司 dev.jelly-tec.com
 * 摘    要：test2.php
 * 作    者：Jelly
 * 修改日期：2017/7/7
 */


include_once dirname(__FILE__) . '/inc/config.php';
include_once dirname(__FILE__) . '/libs/xiaotu/autoload.php';
ini_set('display_errors', 'on');
error_reporting(E_ALL);
header('Content-Type:text/html;charset=UTF-8');

$fileCache = \Xiaotu\Cache\File::getInstance();
$data = [
    'ss' => 'ddd',
    'aaa' => 'ddewf地点',
    [
        'title' => '标题一',
        'content' => '正文1'
    ],
    [
        'title' => '标题2',
        'content' => '正文2'
    ]
];
$fileCache->set('a', $data, 10);
var_dump($fileCache->get('a'));

exit;
$redis = \Xiaotu\Cache\Redis::getInstance();
$arr = [
    [
        'title' => '标题一',
        'content' => '正文1'
    ],
    [
        'title' => '标题2',
        'content' => '正文2'
    ]
];
$redis->set('arr', json_encode($arr));

var_dump(json_decode($redis->get('arr')));
