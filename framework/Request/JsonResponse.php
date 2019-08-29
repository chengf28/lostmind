<?php

namespace Core\Request;

use Core\Request\Response;

/**
 * json格式返回
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class JsonResponse extends Response
{
    private $values;

    public function __construct(array $arr = null)
    {
        header('Content-type:application/json');
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        // 初始化为数组;
        $this->values = [];
        if (!is_null($arr)) {
            $this->arrParse($arr);
        }
    }

    /**
     * 设置参数
     * @param string $name
     * @param mixed $value
     * Real programmers don't read comments, novices do
     */
    public function __set($name, $value)
    {
        $this->values[$name] = $value;
    }

    /**
     * 返回参数
     * @param int $code
     * @param string $codeMsg
     * @return string
     * Real programmers don't read comments, novices do
     */
    public function callback(int $code = 200, string $codeMsg = null)
    {
        $msg = isset(self::$status[$code]) ? self::$status[$code] : $codeMsg;
        header("HTTP/1.1 {$code} {$msg}");
        $data = json_encode($this->values);
        header('Content-Length:' . strlen($data));
        exit($data);
    }

    /**
     * 将数组转换成参数
     * @param array $arr
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function arrParse(array $arr)
    {
        array_walk($arr, function ($value, $key) {
            $this->values[$key] = $value;
        });
    }
}
