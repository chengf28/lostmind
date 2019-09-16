<?php

namespace Core\Templates;

use Core\Exception\Templates\InvalidTemplateArgException;
use Core\Filesystem\Filesystem;

/**
 * 模板编译类
 * 编译模板用途
 * @author chengf28 <chengf_28@163.com>
 * Real programmers don't read comments, novices do
 */
class Compile
{

    protected $footers;

    protected $methods = [
        'extends',
        'section',
        'endsection'
    ];

    public function compile($source, $target)
    {
        $this->footers = [];
        $content = (new Filesystem($source))->get();
        $this->compileVars($content);
        $this->compileStatements($content);
        foreach ($this->footers as $footer) {
            $content .= "\t{$footer}";
        }
        (new Filesystem($target))->put($content);
    }

    /**
     * 处理变量及函数
     * @param string $content
     * @return void
     * Real programmers don't read comments, novices do
     */
    public function compileVars(string &$content)
    {
        $content = preg_replace(
            [
                '~\{\{
                    \s*
                        \$
                        ([A-Za-z_\x7f-\xff][A-Za-z_\-\x7f-\xff\>]*)
                    \s*
                \}\}~x',
                '~\{\{
                    \s*
                    (
                        [
                            A-Za-z_\x7f-\xff\-0-9\>
                        ]+?\([A-Za-z_\-\x7f-\xff\>\'\"]*\)
                        (?=\-\>|\:\:|)?
                        [
                            \[\]A-Za-z0-9\'\"\>\-
                        ]*
                    )
                    \s*
                \}\}~x'
            ],
            [
                '<?php echo $$1;?>',
                '<?php echo $1;?>',
            ],
            $content
        );
        unset($content);
    }

    public function compileStatements(string &$content)
    {
        $content = preg_replace_callback(
            '~\@(?>([A-Za-z]+))[\t]*
                (?:
                    \(
                        [\'\"]*
                        ([A-Za-z0-9_\-\>\[\]\.]*)?
                        [\'\"]*
                    \)?
                )?
            ~x',
            [$this, 'compileStatement'],
            $content
        );
        unset($content);
    }

    protected function compileStatement($match)
    {
        if (in_array($match[1], $this->methods)) {
            return $this->{'compile' . ucfirst($match[1])}($match);
        }
        return $match[0];
    }

    protected function compileExtends(array $match)
    {
        if (!isset($match[2]) || empty($match[2])) {
            throw new InvalidTemplateArgException($match[1] . '() expects at least 1 parameters, 0 given');
        }
        $this->footers[] = "<?php \$this->show('{$match[2]}'); ?>";
        return;
    }

    protected function compileSection(array $match)
    {
        if (!isset($match[2]) || empty($match[2])) {
            throw new InvalidTemplateArgException($match[1] . '() expects at least 1 parameters, 0 given');
        }
        return "<?php \$this->section('{$match[2]}'); ?>";
    }

    protected function compileEndsection(array $match)
    {
        return "<?php \$this->sectionEnd(); ?>";
    }
}
