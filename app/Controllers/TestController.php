<?php
namespace App\Controllers;

use Core\Application;
use Core\Filesystem\Filesystem;
use Core\Request\Request;

class TestController
{
    public function test(Request $req)
    {
        echo "This Is Test Controller";
    }

    public function test2($id, $username, Filesystem $filesystem)
    {
        echo "My name is $username, My Id is $id". PHP_EOL;
        foreach ($filesystem->getLine(__FILE__) as $key => $value) 
        {
            var_dump($value);
        }
    }

    public function test3(Request $req)
    {
        var_dump($req->mehod());
    }
    
}
