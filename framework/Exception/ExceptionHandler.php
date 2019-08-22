<?php
namespace Core\Exception;

use Core\Application;

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
        // 设置自定义错误处理
        set_error_handler([$this,'ErrorHandler']);
        // 设置自定义异常处理
        set_exception_handler([$this,'ExcepHandler']);
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
            
        $exceptionName = get_class($e);
        $msg           = htmlentities($e->getMessage());
        $stack         = '';
        foreach ($e->getTrace() as $key => $value) 
        {
            $stack .= "<p>#{$key} {$value['file']}({$value['line']}):";
            if (isset($value['class'])) 
            {
                $stack .= $value['class'] . $value['type'];
            }
            $stack .= $value['function'] . '(' . implode(',',$value['args']) . ')</p>';
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
                background: #ff6c6c;
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
            #content #error
            {
                box-shadow: 3px 0 3px rgba(0, 0, 0, .2),0 3px 3px rgba(0, 0, 0, .2);
                padding   : 3rem;
                margin    : 1rem;
                width     : 50%;
            }
            #content #error p
            {
                text-align: center;
                font-size: 1.5rem;
                color: #cc5757;
                margin: 1rem 0;
            }

            #content #stack p{
                margin: 1rem 0;
            }
        </style>
        <body>

        <h1>
            {$this->app->getConfig('AppName')}
        </h1>
        <div id="content">
            
            <div id="error">
                <b><h3>{$exceptionName} :</h3></b>
                <p>$msg</p>
                At : <b>{$e->getFile()} Line # {$e->getLine()}</b>
            </div>
            <div id="stack">
                Stack :
                {$stack}
            </div>
        </div>
        </body>
        </html>
HTML;
    }

}