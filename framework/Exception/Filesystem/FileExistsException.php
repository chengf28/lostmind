<?php
namespace Core\Exception\Filesystem;

class FileExistsException extends \Exception
{
    public function __construct($path,$code,$previous = null)
    {
        parent::__construct("File already exits at path $path",$code,$previous);
    }
}