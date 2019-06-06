<?php
namespace Core\Filesystem;

class FileNotFoundException extends \Exception
{
    public function __construct($path,$code,$previous = null)
    {
        parent::__construct('File not found at path:'.$path,$code,$previous);
    }
}