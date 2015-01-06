<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller 
{
    public function index()
    {
    	exit("你知道的非常多啊");
    }


    /**
     * 为了发定时短信而扫描数据库
     * @return [type] [description]
     */
    public function scanForSendSMS()
    {

    }
}