<?php
namespace Home\Controller;
use Think\Controller;

class ScheduleController extends Controller
{
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
    
	protected $uid = null;

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
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function create()
    {
        $dbSchedule     =   D("Schedule");
        $data   =   null;

        // $data["title"]   =   I('param.title');
        // $data["tag"]   =   I('param.tag');
        // $data["location"]   =   I('param.location');
        // $data["startTime"]   =   I('param.startTime');
        // $data["endTime"]   =   I('param.endTime');
        // $data["content"]   =   I('param.content');
        // $data["check"]   =   I('param.check');//json
        // $data["participant"]   =   I('param.participant');//json

        $dbSchedule->field("title,location,startTime,endTime,content")->create(I('param.'));
        $dbSchedule->tag = I('param.tag',"null",false);
        $dbSchedule->check = I('param.check',"null",false);
        $dbSchedule->participant = I('param.participant',"null",false);

        //TODO:自动补全和验证
        if (!$dbSchedule->tagValidateRules($dbSchedule->tag))
            exit("error");
        if (!$dbSchedule->checkValidateRules($dbSchedule->check))
            exit("error");
        if (!$dbSchedule->participantValidateRules($dbSchedule->participant))
            exit("error");

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
        // $data = $dbSchedule->field("sid,check")->create(I("param."));
        $data = I("param.check","null",false);
        dump($data);
        // echo $dbSchedule->check;
        dump(json_decode($dbSchedule->check,true));
        //$dbSchedule->data   =   json_encode($dbSchedule->data);
        // $dbSchedule->add();
    }
}