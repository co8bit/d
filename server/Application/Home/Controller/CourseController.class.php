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

        // $a = exec("ls",$out,$status);
        // $a = exec("./getCourse/grabber.py 3110000201 gfjhg 3110000201e.json 3110000201c.json",$out,$status);
        // $a = exec("./getCourse/grabber.py ".$userNo." ".$pwd." ".$userNo."e.json "." ".$userNO."c.json",$out,$status);
        // if ($status == 1)//TODO:是否可以这样判断
        //     exit("用户名密码错误");

        // $filename = "./getCourse/c.json";
        // $json_string = file_get_contents($filename);
        // $courseString = json_decode($json_string);
        // dump($courseString);

        exit("true");
    }

}