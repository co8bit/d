<?php
namespace Home\Controller;
use Think\Controller;

class ScheduleController extends Controller
{
    /*ReturnJson:
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
            "state": 1
        },
        {
            "content": "nice",
            "state": 0
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
        empty($this->uid) && $this->error("error",U("Index/login"));
    }

    /**
    *查询某个日程
    *@param int sid
    *@return error 错误
    *@return ReturnJson 返回数据
    */
    public function query()
    {
        $dbSchedule = D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));

        $result    =   $dbSchedule->where(array("sid"=>$sid))->find();
        $result["tag"] = json_decode($result["tag"],true);
        $result["check"] = json_decode($result["check"],true);
        $result["participant"] = json_decode($result["participant"],true);
        $this->ajaxReturn($result);
    }

    /**
     * 创建一个日程
     * @param 多个参数，名称参考returnJson,但是形式是post
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function create()
    {
        $dbSchedule     =   D("Schedule");
        $data   =   null;

        // $data["title"]   =   I('param.title');
        // $data["tag"]   =   I('param.tag');//json
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

        $tmp    =   $dbSchedule->add();
        if(empty($tmp))//添加失败
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }



    /**
     * 修改一个日程
     * @param 多个参数，名称参考returnJson,但是形式是post
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function edit()
    {
        $dbSchedule     =   D("Schedule");
        $data   =   null;

        $dbSchedule->field("sid,title,location,startTime,endTime,content")->create(I('param.'));
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

        $tmp    =   null;
        $tmp = $dbSchedule->save();
        if( ($tmp === null) || ($tmp === false) )
        {
            echo "false";
        }
        else
        {
            echo "true";
        }
    }


    /**
     * 删除一个日程
     * @param int sid
     * @return bool "" 是否成功
     */
    public function delete()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));
        if ($dbSchedule->where(array("sid"=>$dbSchedule->sid))->delete())
            exit("true");
        else
            exit("false");
    }



    /**
     * 添加一项CheckItem
     * @param int sid
     * @param string content 检查项名称
     * @param bool state 检查项状态
     * @return bool "" 是否成功
     * @return error "" 错误
     */
    public function addCheck()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));
        $data["content"]    =   I("param.content","");
        $data["state"]      =   (int)I("param.state","");

        if (!$dbSchedule->checkValidateRules($data))
            exit("error");

        $tmp    =   $dbSchedule->where(array("sid"=>$dbSchedule->sid))->find();
        $tmp["check"]   =   json_decode($tmp["check"],true);
        array_push($tmp["check"],array("content"=>$data["content"],"state"=>$data["state"]));
        $tmp2   =   nnull;
        $tmp2   =   json_encode($tmp["check"]);
        if ($dbSchedule->save(array("sid"=>$dbSchedule->sid,"check"=>$tmp2)))
            exit("true");
        else
            exit("false");
    }



    /**
     * 修改全部Check，注意是全部check，因为区分不出来修改第几个check
     * @param int sid
     * @param ReturJson_check check 全部check的json
     * @return bool "" 是否成功
     * @return error "" 错误
     */
    public function editCheck()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));
        $check = I('param.check',"null",false);
        if (!$dbSchedule->checkValidateRules($check))
            exit("error");

        $tmp    =   $dbSchedule->save(array("sid"=>$dbSchedule->sid,"check"=>$check));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }
}