<?php
/**
 * Copyright (c) 2017, 杰利信息科技有限公司 dev.jelly-tec.com
 * 摘    要：autoload.php
 * 作    者：wangld
 * 修改日期：2017/6/19
 */

class XiaotuAutoLoad
{
    /**
     * 功    能：加载xiaotu类库
     * 修改日期：2017-6-19
     *
     * @param string $className 类名
     *
     * @return null 无返回
     */
    private static function loadXiaotuBase($className)
    {
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.class.php';
        $classFile = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $classPath;
        if (is_file($classFile))
        {
            include_once "$classFile";
        }
    }

    /**
     * 功    能：注册自动加载函数
     * 修改日期：2017-6-19
     *
     * @return null 无返回
     */
    public static function autoload()
    {
        spl_autoload_register(array('XiaotuAutoLoad', 'loadXiaotuBase'));
    }
}

XiaotuAutoLoad::autoload();
