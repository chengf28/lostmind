<?php
namespace App\Controllers;

use Core\Application;

class TestController
{
    public function test()
    {
        echo "This Is Test Controller";
    }

    public function test2($id, $username)
    {
        echo "My name is $username, My Id is $id". PHP_EOL;
    }
    
}
