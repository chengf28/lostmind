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
    protected $suffix = '.lm.php';

    protected $cache_suffix = '.php';

    public function getSouceName(string &$filename)
    {
        $filename = config('base.viewPath') . str_replace('.', DIRECTORY_SEPARATOR, $filename) . $this->suffix;
    }

    public function getTargetName(string $filename)
    {
        return config('base.templates_storage') . DIRECTORY_SEPARATOR . sha1_file($filename) . $this->cache_suffix;
    }

    public function compile($source)
    {
        $this->getSouceName($source);
        $target = $this->getTargetName($source);
        if (!Filesystem::has($target)) {
            $source = new Filesystem($source);
            foreach ($source->line() as $ctx) {
                if (strpos($ctx,'{{') !== false) {
                    $ctx = $this->parse($ctx);
                }

                var_dump($ctx);
            }
        }
    }

    public function parse($content)
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
}
