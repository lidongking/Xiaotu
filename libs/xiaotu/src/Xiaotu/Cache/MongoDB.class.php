<?php
/**
 * Copyright (c) 2017, 杰利信息科技有限公司 dev.jelly-tec.com
 * 摘    要：MongoDB.php
 * 作    者：Jelly
 * 修改日期：2017/7/7
 */

namespace Xiaotu\Cache;

use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Query;
use MongoDB\Driver\Command;
use Exception;
use MongoDB\Driver\WriteConcern;

class MongoDB
{
    protected static $instances = [];

    protected $db = null;

    protected $manager = null;
    protected $writeConcern;

    /**
     * MongoDB constructor.
     *
     * @param array $config 配置
     * @throws Exception 异常抛出
     */
    protected function __construct($config)
    {
        if (empty($config) || empty($config['uri']))
        {
            throw new Exception('MongoDB dbKey: ' . $config['dbKey'] . ' is not good.');
        }

        $this->manager = new Manager($config['uri'], $config['options']);
        //$this->manager->executeCommand('test', new Command(['ping' => 1]));
        //$this->writeConcern = $this->manager->getWriteConcern();
        $this->writeConcern = new WriteConcern(WriteConcern::MAJORITY, 1000);
        $this->db = $config['dbName'];
    }

    /**
     * 功    能：获取操作实例
     * 修改日期：2017-7-4
     *
     * @param string $dbKey 实例key
     *
     * @return MongoDB 返回实例
     */
    public static function getInstance($dbKey = 'DEFAULT')
    {
        $dbKey = strtoupper($dbKey);
        if (!isset(self::$instances[$dbKey]))
        {
            global $CFG;

            $config = $CFG['mongo'][$dbKey];
            $config['dbKey'] = $dbKey;

            self::$instances[$dbKey] = new static($config);
        }

        return self::$instances[$dbKey];
    }

    /**
     * 功    能：插入文档
     * 修改日期：2017-7-7
     *
     * @param string $collection 集合名称
     * @param array $document 文档
     *
     * @return \MongoDB\Driver\WriteResult 结果
     */
    public function insert($collection, array $document)
    {
        $bulkWrite = new BulkWrite;
        $bulkWrite->insert($document);

        return $this->manager->executeBulkWrite($this->db . '.' . $collection, $bulkWrite, $this->writeConcern);
    }

    /**
     * 功    能：批量插入文档
     * 修改日期：2017-7-7
     *
     * @param string $collection 集合名称
     * @param array $documents 多重文档
     *
     * @return \MongoDB\Driver\WriteResult 结果
     */
    public function batchInsert($collection, array $documents)
    {
        $bulkWrite = new BulkWrite;
        foreach ($documents as $document)
        {
            $bulkWrite->insert($document);
        }

        return $this->manager->executeBulkWrite($this->db . '.' . $collection, $bulkWrite, $this->writeConcern);
    }

    /**
     * 功    能：删除文档
     * 修改日期：2017-7-7
     *
     * @param string $collection 集合
     * @param array $filter 筛选条件
     * @param array $options 额外选项
     *
     * @return \MongoDB\Driver\WriteResult 结果
     */
    public function delete($collection, array $filter, array $options = [])
    {
        $bulkWrite = new BulkWrite;
        $bulkWrite->delete($filter, $options);

        return $this->manager->executeBulkWrite($this->db . '.' . $collection, $bulkWrite, $this->writeConcern);
    }

    /**
     * 功    能：更新文档
     * 修改日期：2017-7-7
     *
     * @param string $collection 集合
     * @param array $filter 筛选条件
     * @param array $update 待更新的数据
     * @param array $options 选项
     *
     * @return \MongoDB\Driver\WriteResult 结果
     */
    public function update($collection, array $filter, array $update, $options = [])
    {
        $bulkWrite = new BulkWrite;
        $bulkWrite->update($filter, ['$set' => $update], $options);

        return $this->manager->executeBulkWrite($this->db . '.' . $collection, $bulkWrite, $this->writeConcern);
    }

    /**
     * 功    能：查询文档
     * 修改日期：2017-7-7
     *
     * @param string $collection 集合
     * @param array $filter 筛选条件
     * @param array $options 选项
     *
     * @return array 结果
     */
    public function find($collection, array $filter, $options = [])
    {
        $query = new Query($filter, $options);
        $cursor = $this->manager->executeQuery($this->db . '.' . $collection, $query);

        return $cursor->toArray();
    }

    /**
     * 功    能：执行命令
     * 修改日期：2017-7-18
     *
     * @param Command $command Command参数
     *
     * @return \MongoDB\Driver\Cursor 结果
     */
    public function executeCommand(Command $command)
    {
        return $this->manager->executeCommand($this->db, $command);
    }

    /**
     * 功    能：获取manager
     * 修改日期：2017-7-7
     *
     * @return \MongoDB\Driver\Manager|null 结果
     */
    public function getManager()
    {
        return $this->manager;
    }

    public function count($collection, array $filter)
    {
        $document = [
            'count' => $collection,
            'filter' => $filter
        ];
        $command = new Command($document);
        $cursor = $this->executeCommand($command);
        $res = $cursor->toArray();

        return $res[0]->n;
    }
}
