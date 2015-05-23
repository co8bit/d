<?php
namespace Home\Controller;
use Think\Controller;

class CourseController extends Controller
{
 	protected $uid = null;

	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->uid		=		session("uid");
        empty($this->uid) && $this->error("error",U("Index/login"));
    }



    public function login()
    {
        $userNo     =   I("param.userNo");//学号
        $pwd        =   I("param.pwd");//密码


    }

}