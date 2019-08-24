<?php

namespace Core\Exception;

use Core\Application;
use Core\Exception\FatalErrorException;

/**
 * 异常处理
 * @author chengf28 <chengf_28@163.com>
 * If I can go death, I will
 */
class ExceptionHandler
{
    protected $app;
    /**
     * 初始化
     * If I can go death, I will
     */
    public function __construct(Application $app)
    {
        // error_reporting(0);
        // 设置自定义错误处理
        set_error_handler([$this, 'ErrorHandler']);
        // 设置自定义异常处理
        set_exception_handler([$this, 'ExcepHandler']);
        // register_shutdown_function([$this, 'ShudownHandler']);
        $this->app  = $app;
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
    public function ErrorHandler(int $errno, string $errstr, string $errfile, string $errline)
    {
        // 将错误转成错误异常抛出
        throw new \ErrorException($errstr, $errno, $errno, $errfile, $errline);
    }

    public function ShudownHandler()
    {
        $error = error_get_last();
        if ($error && $error['type'] === E_ERROR){
            $excep = new FatalErrorException($error['message'],0,1,$error['file'],$error['line']);
            $this->ExcepHandler($excep);
        }
    }

    /**
     * 异常处理
     * @param \Throwable $e
     * @return void
     * If I can go death, I will
     */
    public function ExcepHandler(\Throwable $e)
    {
        
        if ($this->app->getConfig('base.debug')) {
            $exceptionName = get_class($e) . ':';
            $msg           = htmlentities($e->getMessage());
            $stack         = 'Stack :';
            $position      = "At : <b>{$e->getFile()} Line # {$e->getLine()}</b>";
            foreach ($e->getTrace() as $key => $value) {
                $stack .= "<p>#{$key} ";
                if (isset($value['file'])) {
                    $stack .= "{$value['file']}({$value['line']}):<span>";
                }
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
                        $stack .= htmlspecialchars($args) . ',';
                    }
                    $stack = trim($stack,',');
                }
                $stack .= ')</span></p>';
            }
            ++$key;
            $stack  .= "<p>#{$key} {main}</p>";
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
            <title>{$this->app->getConfig('AppName')}</title>
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

            h1 {
                height: 6rem;
                line-height: 6rem;
                width: 100%;
                text-align: center;
                background: #677470;
                color: #fff;
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
                background: #ad5d54;
            }
            #error p
            {
                text-align: center;
                font-size: 1.5rem;
                color: #fff;
                margin: 1rem 0;
            }
            #content #stack p{
                margin: 1rem 0;
            }
            #content #stack p span{
                color:#ad5d54;
                font-weight:bold;
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
