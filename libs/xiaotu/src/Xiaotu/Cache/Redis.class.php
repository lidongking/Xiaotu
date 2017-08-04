<?php
/**
 * Copyright (c) 2017, 杰利信息科技有限公司 dev.jelly-tec.com
 * 摘    要：Redis.class.php
 * 作    者：Jelly
 * 修改日期：2017/7/7
 */

namespace Xiaotu\Cache;

use Exception;
// 转换名称，类名冲突
use Redis as RedisORG;

class Redis
{
    public $object = null;
    protected static $instances = [];

    /**
     * Redis constructor.
     *
     * @param array $config 配置
     *
     * @throws Exception 异常抛出
     */
    protected function __construct($config = [])
    {
        if (empty($config) || empty($config['host']))
        {
            throw new Exception('Redis dbKey: ' . $config['dbKey'] . ' is not good.');
        }
        $this->object = new RedisORG();
        $this->object->connect($config['host'], $config['port']);
        if ($config['auth'])
        {
            $this->object->auth($config['auth']);
        }
    }

    /**
     * 功    能：获取Redis实例
     * 修改日期：2017-7-7
     *
     * @param string $dbKey 配置标示
     *
     * @return RedisORG Redis 实例
     */
    public static function getInstance($dbKey = 'DEFAULT')
    {
        $dbKey = strtoupper($dbKey);
        if (!isset(self::$instances[$dbKey]))
        {
            global $CFG;

            $config = $CFG['redis'][$dbKey];
            $config['dbKey'] = $dbKey;

            self::$instances[$dbKey] = new static($config);
        }

        return self::$instances[$dbKey];
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->object, $name], $arguments);
    }
}
