<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller
{
	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
    }

    public function index()
    {
    	//$this->show("asd");
    	echo "你知道的太多了<br>";
    	echo "注册地址：".U("User/sign");
    }
}