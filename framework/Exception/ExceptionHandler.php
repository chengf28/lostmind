<?php

namespace Core\Exception;

use Core\Application;
use Core\Exception\FatalErrorException;

/**
 * 异常处理
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class ExceptionHandler
{
    protected $app;
    /**
     * 初始化
     * Real programmers don't read comments, novices do
     */
    public function __construct(Application $app)
    {
        error_reporting(0);
        // 设置自定义错误处理
        set_error_handler([$this, 'ErrorHandler']);
        // 设置自定义异常处理
        set_exception_handler([$this, 'ExcepHandler']);
        register_shutdown_function([$this, 'ShudownHandler']);
        $this->app  = $app;
    }

    /**
     * 将错误转换成异常抛出
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function ErrorHandler(int $errno, string $errstr, string $errfile, string $errline)
    {
        // 将错误转成错误异常抛出
        throw new \ErrorException($errstr, $errno, $errno, $errfile, $errline);
    }

    public function ShudownHandler()
    {
        $error = error_get_last();
        if ($error && ($error['type'] === E_ERROR || $error['type'] === E_COMPILE_ERROR)){
            $excep = new FatalErrorException($error['message'],0,1,$error['file'],$error['line']);
            $this->ExcepHandler($excep);
        }
    }

    /**
     * 异常处理
     * @param \Throwable $e
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function ExcepHandler(\Throwable $e)
    {
        /**
         * 当存在缓冲区时，清空缓冲区
         */
        if(ob_get_level())
        {
            ob_clean();
        }
        if ($this->app->getConfig('base.debug')) {
            $exceptionName = get_class($e) . ':';
            $msg           = htmlentities($e->getMessage());
            $stack         = 'Stack :';
            $position      = "At : <b>{$e->getFile()} Line # {$e->getLine()}</b>";
            foreach ($e->getTrace() as $key => $value) {
                $stack .= "<p><i>#{$key}</i> ";
                if (isset($value['file'])) {
                    $stack .= "<span>{$value['file']}({$value['line']}):</span>  ";
                }

                $stack .= '<b>';
                if (isset($value['class'])) {
                    $stack .= $value['class'] . $value['type'];
                }
                $stack .= $value['function'] . '(';
                if (isset($value['args'])) 
                {
                    foreach ($value['args'] as $args) 
                    {
                        if (is_array($args)) {
                          $args = str_replace(['{','}',':'],['[',']','=>'],json_encode($args));
                        }
                        if (is_object($args)) 
                        {
                            $args = get_class($args);
                        }
                        $stack .= htmlspecialchars((string)$args) . ',';
                    }
                    $stack = trim($stack,',');
                }
                $stack .= ')</b></p>';
            }
            ++$key;
            $stack  .= "<p><i>#{$key}</i> <b>{main}<b></p>";
        } else {
            $msg           = 'Some thing error';
            $exceptionName = '';
            $stack         = '';
            $position      = '';
        }
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="zh_cn">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Exception</title>
        </head>
        <style>
            * {
                padding: 0;
                margin: 0
            }
            html,
            body {
                height: 100%;
            }

            #content{
                overflow: auto;
                padding: 1rem;
                margin: 0 auto;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
                word-break: break-word;                
            }
            #error
            {
                padding   : 3rem;
                box-sizing:border-box;
                width     : 100%;
                background: #6595ac;
            }
            #error p
            {
                text-align: center;
                font-size: 1.5rem;
                color: #fff;
                margin: 1rem 0;
            }
            #content #stack{
                padding:50px 10%;
                box-sizing:border-box;
            }
            #content #stack p{
                margin: 1rem 0;
                color:#6595ac;
                font-weight:bold;
                font-size:.9rem;
            }
            #content #stack p > i
            {
                color:#000;
                font-weight:normal;
            }

            #content #stack p > b
            {
                width: 100%;
                display: inline-block;
                font-size: 1.1rem;
                text-indent:2.2rem;
            }
            #content #stack p span{
                color:#000;
                font-weight:normal;
            }
        </style>
        <body>
        <div id="content">
            <div id="error">
                <b>{$exceptionName}</b>
                <p>
                    $msg
                </p>
                $position
            </div>
            <div id="stack">
                {$stack}
            </div>
        </div>
        </body>
        </html>
HTML;
    }

}
