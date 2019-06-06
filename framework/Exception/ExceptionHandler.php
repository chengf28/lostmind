<?php
namespace Core\Exception;

/**
 * 异常处理
 * @author chengf28 <chengf_28@163.com>
 * If I can go death, I will
 */
class ExceptionHandler
{
    /**
     * 初始化
     * If I can go death, I will
     */
    public function __construct()
    {
        // 设置自定义错误处理
        set_error_handler([$this,'ErrorHandler']);
        // 设置自定义异常处理
        set_exception_handler([$this,'ExcepHandler']);
    }

    /**
     * 将错误转换成异常抛出
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     * @return void
     * If I can go death, I will
     */
    public function ErrorHandler( int $errno , string $errstr , string $errfile , string $errline )
    {
        // 将错误转成错误异常抛出
        throw new \ErrorException($errstr,$errno,$errno,$errfile,$errline);
    }

    /**
     * 异常处理
     * @param \Throwable $e
     * @return void
     * If I can go death, I will
     */
    public function ExcepHandler( \Throwable $e )
    {
        var_dump($e->getMessage());
        var_dump($e->getTraceAsString());
    }

}