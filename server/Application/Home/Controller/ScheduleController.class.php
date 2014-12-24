<?php
namespace Home\Controller;
use Think\Controller;

class ScheduleController extends Controller
{
	protected $uid = null;

    /*returnJson:
     {
       "title": "George",
       "tag": ["pig"],
       "location": "zju",
       "startTime": 123123,
       "endTime": 3242342,
       "content": "nicai",
       "check": [
        {
            "content": "nicaibudao",
            "state": true
        },
        {
            "content": "nice",
            "state": true
        }
      ],
       "participant": [
        321,
        67
       ]
     }
     */
    
	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->uid		=		session("uid");
        empty($this->uid) && $this->error("非法操作",U("Index/login"));
    }

    /**
    *查询某个日程
    *@param int sid
    *@return error 错误
    *        或returnJson
    */
    public function query()//TODO:
    {
        $dbSchedule = D("Schedule");

        $sid    =   I('param.sid');
        empty($sid) && exit("error");

        $result    =   $dbSchedule->where(array("sid"=>$sid))->find();
        // dump($result);
        $result["check"] = json_decode($result["check"],true);
        // dump($result);
        //$this->ajaxReturn($result);
    }

    /**
     * 创建一个日程
     * @param 多个参数，名称参考returnJson
     * @return bool "" 是否成功
     */
    public function create()
    {
        $dbSchedule     =   D("Schedule");
        $data   =   null;

        // $data["title"]   =   I('param.');
        // $data["tag"]   =   I('param.');
        // $data["location"]   =   I('param.');
        // $data["startTime"]   =   I('param.');
        // $data["endTime"]   =   I('param.');
        // $data["content"]   =   I('param.');
        // $data["check"]   =   I('param.');//json
        // $data["participant"]   =   I('param.');//json

        $dbSchedule->create();
        //TODO:自动补全和验证

        if(empty($dbSchedule->add()))//添加失败
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }

    public function addCheck()
    {
        $dbSchedule     =   D("Schedule");
        $data = $dbSchedule->create();
        dump($data);
        echo $dbSchedule->check;
        dump(json_decode($dbSchedule->check,true));
        //$dbSchedule->data   =   json_encode($dbSchedule->data);
        // $dbSchedule->add();
    }
}