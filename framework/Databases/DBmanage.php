<?php

namespace Core\Database;

use Core\Application;
use Core\Exception\Databases\CreateConnectException;
use DBquery\Builder\QueryBuilder;
use DBquery\Connect\Connect;
use PDO;
use Throwable;

/**
 * DB管理器
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class DBmanage
{
    /**
     * App核心
     * @var \Core\Application
     * Real programmers don't read comments, novices do
     */
    protected $app;

    /**
     * connect类
     * @var \DBquery\Connect\Connect
     * Real programmers don't read comments, novices do
     */
    protected $connect;

    /**
     * 多库的选择
     * @var array
     * Real programmers don't read comments, novices do
     */
    protected $select;


    /**
     * 初始化
     * @param \Core\Application $app
     * Real programmers don't read comments, novices do
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 创建connect
     * @param bool $hasSelect
     * @return void
     * @throws \Core\Exception\Databases\CreateConnectException
     * Real programmers don't read comments, novices do
     */
    protected function createConnect(bool $hasSelect = false)
    {
        $config       = $this->app->getConfig('DBconfig');
        try {
            $connect = new Connect;
            foreach (['write', 'read'] as  $value) {
                if (!$hasSelect) {
                    $this->select[$value] = array_rand($config[$value]['dsn'], 1);
                }
                $method = 'set' . ucfirst($value);
                $connect->{$method}(
                    new PDO(
                        $config['type'] . ':' . $config[$value]['dsn'][$this->select[$value]],
                        isset($config[$value]['db_username'][$this->select[$value]]) ? $config[$value]['db_username'][$this->select[$value]] : end($config[$value]['db_username']),
                        isset($config[$value]['db_password'][$this->select[$value]]) ? $config[$value]['db_password'][$this->select[$value]] : end($config[$value]['db_password'])
                    )
                );
            }

            $this->connect = $connect;
        } catch (Throwable $e) {
            throw new CreateConnectException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 修改选择的connect
     * @param int $select
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function connect(int $select)
    {

        $this->select['read'] = isset($this->app->getConfig('DBconfig')['read']['dsn'][$select]) ? $this->app->getConfig('DBconfig')['read']['dsn'][$select] : end($this->app->getConfig('DBconfig')['read']['dsn']);

        $this->select['write'] = isset($this->app->getConfig('DBconfig')['write']['dsn'][$select]) ? $this->app->getConfig('DBconfig')['write']['dsn'][$select] : end($this->app->getConfig('DBconfig')['write']['dsn']);

        $this->createConnect(true);
    }

    /**
     * 获取到Connect
     * @return \DBquery\Connect\Connect
     * Real programmers don't read comments, novices do
     */
    protected function getConnect()
    {
        if (!$this->connect) {
            $this->createConnect();
        }
        return $this->connect;
    }

    /**
     * 新建原始数据
     * @param string $string
     * @return \DBquery\Common\QueryStr
     * Real programmers don't read comments, novices do
     */
    public function raw(string $string)
    {
        return new QueryStr($string);
    }

    /**
     * 开始事务
     * @return void
     * God Bless the Code
     */
    public function beginTransaction()
    {
        $this->getConnect()->transaction();
    }

    /**
     * 回滚
     * @return void
     * God Bless the Code
     */
    public function rollback()
    {
        $this->getConnect()->rollback();
    }

    /**
     * 提交
     * @return void
     * God Bless the Code
     */
    public function commit()
    {
        $this->getConnect()->commit();
    }

    /**
     * 调用DBquery中的数据
     * @param string $method
     * @param mixed $args
     * @return \DBquery\Builder\QueryBuilder
     * Real programmers don't read comments, novices do
     */
    public function __call($method, $args)
    {
        return (new QueryBuilder($this->getConnect()))->setPrefix($this->app->getConfig('DBconfig')['prefix'])->{$method}(...$args);
    }
}
