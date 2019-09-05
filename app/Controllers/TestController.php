<?php

namespace App\Controllers;

use Core\Application;
use Core\Facade\App;
use Core\Request\Request;

class TestController
{
    public function test(Application $app)
    {
        view('test.test',['test'=>'aaaa']);
    }

    public function test2($id, $username)
    {
        echo "My name is $username, My Id is $id" . PHP_EOL;
    }

    public function test3(Request $req)
    {
        
    }
}