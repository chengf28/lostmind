<?php

namespace Core\Templates;

use Core\Application;
use Core\Filesystem\Filesystem;
use Core\Exception\Filesystem\TemplateNotFoundException;

/**
 * 模板编译类
 * 编译模板用途
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class Compile
{
    protected $suffix       = '.lm.php';

    protected $cache_suffix = '.php';

    protected $methods = [
        'extends',
        'section'
    ];

    public function getSouceName(string &$filename)
    {
        $filename = config('base.viewPath') . str_replace('.', DIRECTORY_SEPARATOR, $filename) . $this->suffix;
    }

    public function getTargetName(string $filename)
    {
        return config('base.templates_storage') . DIRECTORY_SEPARATOR . sha1($filename) . $this->cache_suffix;
    }

    public function compile($source)
    {
        $this->getSouceName($source);
        $target = $this->getTargetName($source);
        if (!Filesystem::has($target)) {
            $content = (new Filesystem($source))->get();
            var_dump($content);
        }
    }

    public function variablesParse($content)
    {
        return preg_replace(
            [
                '~\{\{\s*\$([A-Za-z_\x7f-\xff][A-Za-z_\-\x7f-\xff\>]*)\s*\}\}~x',
                '~\{\{\s*([A-Za-z_\x7f-\xff\-0-9\>]+?\([A-Za-z_\-\x7f-\xff\>\'\"]*\)(?=\-\>|\:\:|)[\[\]A-Za-z0-9\'\"\>\-]*)\s*\}\}~x'
            ],
            [
                '<?php echo $1;?>',
                '<?php echo $1;?>',
            ],
            $content
        );
    }

    public function methodsParse($content)
    {
        if (
            !preg_match(
                '~\@([A-Za-z]+?)\(([A-Za-z0-9_\-\>\'\"\[\]\.]*)\)~x',
                $content,
                $res
            )
        ) {
            return $content;
        }

        if (!in_array($res[1], $this->methods)) {
            return $content;
        }

        $this->{$res[1]}($res[2]);
    }

    public function extends(){

    }

    public function section()
    {

    }
}
