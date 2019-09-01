<?php
namespace Core\Templates;

use Core\Filesystem\Filesystem;

/**
 * 模板编译
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class CompileTemplate
{
    public function compile($source,$targe)
    {
        $file       = new Filesystem($source);
        $targe_file = new Filesystem($targe);
        foreach ( $file->line() as $value) {
            /**
             * 替换变量
             */
            $rep = preg_replace(
                '~\{\{(\$[A-Za-z0-9\-_]+?)\}\}~x',
                '<?php echo $1; ?>',
                $value
            );
            $targe_file->put($rep);
        }
        unset($file,$targe_file);
    }
}