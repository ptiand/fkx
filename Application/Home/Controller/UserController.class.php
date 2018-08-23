<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{
    protected $userId = null;
    public function _initialize()
    {
        $user = get_user();
        if(!$user){
            $path = $_SERVER["REQUEST_URI"];
            session("back" , $path);
//            echo('hello:' . $path);
//            exit();
            $this -> redirect('Login/code_login');
            return;
        }
        $this->userId = $user['id'];
    }
}