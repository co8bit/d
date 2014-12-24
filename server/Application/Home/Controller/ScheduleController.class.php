<?php
namespace Home\Controller;
use Think\Controller;

class ScheduleController extends Controller
{
	protected $uid = null;

	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->uid		=		session("uid");
        empty($this->uid) && $this->error("非法操作",U("Index/login"));
    }

    /**
    *查询某个日程
    *@return 
    public function query()
    {

    }
}