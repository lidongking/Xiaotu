<?php
/**
 * Copyright (c) 2017, 杰利信息科技有限公司 dev.jelly-tec.com
 * 摘    要：Helper.class.php
 * 作    者：Jelly
 * 修改日期：2017/8/2
 */

namespace Xiaotu\Http;

class Helper
{
    public static function http_curl($url, $method = 'get', $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        


    }

    public static function check($val)
    {
        return isset($val) && $val;
    }
}
