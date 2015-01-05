<?php
namespace Home\Controller;
use Think\Controller;

require_once(APP_PATH."/Home/Conf/MyConfigINI.php");

class BugController extends Controller
{
	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->uid      =       session("uid");
        empty($this->uid) && $this->error("error",U("Index/login"));
    }

    /**
     * 提交bug或者建议
     * @param 需要提交的字段如下：
     *     level int,//紧急或重要程度
     *     title TEXT,
     *     class int,//0是bug，1是建议
     *     content text,//这里应该是富文本编辑器的内容
     *     qq varchar(20) not null,
     *     email varchar(100) not null,
     *     phone varchar(20) not null, 
     * @return bool "" 是否成功
     */
    public function add()
    {
    	$dbBug     =   D("Bug");

        $dbBug->create(I(_INPUT_METHOD));
        $dbBug->uid     =   $this->uid;
        $dbBug->createTime  =   date("Y-m-d H:i:s");
        $dbBug->state   =   0;
        if ($dbBug->add())
            exit("true");
        else
            exit("false");

    }
}