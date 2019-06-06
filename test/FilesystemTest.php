<?php
namespace Test;
use PHPUnit\Framework\TestCase;
use Core\Filesystem\Filesystem;


class FilesystemTest extends TestCase
{
    public $rootPath;

    public $filename;

    public function __construct()
    {
        $this->rootPath = dirname(__DIR__).DIRECTORY_SEPARATOR;
        $this->filename = $this->rootPath.'test.txt';
        parent::__construct();
    }

    
    public function testput()
    {
        $file = new Filesystem;
        $string = 'test';
        $file->put($this->filename,$string);
        $this->assertFileExists($this->filename);
        return $string;
    }
    
    /**
     * @depends testput
     */
    public function testget($string)
    {
        $file = new Filesystem;
        $this->assertEquals($string, $file->get($this->filename));
        return $this->filename;
    }
    
    /**
     * @depends testget
     */
    public function testhas($filename)
    {
        $file     = new Filesystem;
        $this->assertTrue(
            $file->has($filename)
        );
        return $filename;
    }

    /**
     * @depends testhas
     */
    public function testmove($filename)
    {
        $file = new Filesystem;
        $newfilename = $this->rootPath.'test/test.txt';
        $file->move($filename,$newfilename);
        $this->assertFileExists($newfilename);
        return $newfilename;
    }

    /**
     * @depends testmove
     */
    public function testcopy($filename)
    {
        $file = new Filesystem;
        $newfilename = $this->rootPath.'test/test-copy.txt';
        $file->copy($filename,$newfilename);
        $this->assertFileExists($newfilename);
        return [$newfilename,$filename];
    }
    
    /**
     * @depends testcopy
     */
    public function testdelete($filename)
    {
        $file = new Filesystem;
        $file->delete($filename[0]);
        $this->assertFalse(file_exists($filename[0]));
        $file->delete($filename[1]);
        $this->assertFalse(file_exists($filename[1]));
    }
}
