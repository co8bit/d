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
     * 整理二维数组中的json格式，以使得ajaxReturn可以进行正常的含json嵌套的输出
     * @param  array[][] $data 要被整理的数据
     * @return array[][] 整理完成的数据
     */
    protected function trimForAjax($data = null)
    {
        foreach ($data as $key1=>$value1)
        {
            foreach ($data[$key1] as $key2=>$value2)
            {
                if ( ($key2 == "tag") || ($key2 == "check") || ($key2 == "participant") || ($key2 == "comment") )
                {
                    $data[$key1][$key2]     =   json_decode($value2,true);
                }
            }
        }

        return $data;
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

        $result    =   $dbSchedule->where(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid))->find();
        $result["tag"] = json_decode($result["tag"],true);
        $result["check"] = json_decode($result["check"],true);
        $result["participant"] = json_decode($result["participant"],true);
        $this->ajaxReturn($result);
    }

    /**
     * 创建一个日程
     * @param 多个参数，名称参考returnJson,但是形式是post
     * @param int mode 模式，为0新建日程，为2/3/4，和活动的class一致（需要传活动的aid到日程的aid字段里，代表链接到活动上）
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function create($mode = 0)
    {
        $dbSchedule     =   D("Schedule");
        $data   =   null;

        $dbSchedule->field("title,location,startTime,endTime,content,aid,class")->create(I('param.'));
        $dbSchedule->uid =  $this->uid;
        $dbSchedule->tag = I('param.tag',"null",false);
        $dbSchedule->participant = I('param.participant',"null",false);
        $dbSchedule->state = 0;

        
        $mode   =   I("param.mode",0);
        if ((int)$mode == 0)
        {
            $dbSchedule->check = I('param.check',"null",false);
            if (!$dbSchedule->checkValidateRules($dbSchedule->check))
                exit("error");

            $dbSchedule->class  =   0;
        }
        

        //TODO:自动补全和验证
        if (!$dbSchedule->tagValidateRules($dbSchedule->tag))
            exit("error");
        if (!$dbSchedule->participantValidateRules($dbSchedule->participant))
            exit("error");
		$tmp = $dbSchedule->add();
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
     * @param 多个参数，名称参考returnJson,但是形式是post; class、aid不能修改
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function edit()
    {
        $dbSchedule     =   D("Schedule");
        $data   =   null;

        $dbSchedule->field("sid,title,location,startTime,endTime,content,state")->create(I('param.'));
        $dbSchedule->uid =  $this->uid;
        $dbSchedule->tag = I('param.tag',"null",false);
        $dbSchedule->participant = I('param.participant',"null",false);
        $dbSchedule->check = I('param.check',"null",false);

        if (!$dbSchedule->checkValidateRules($dbSchedule->check))
            exit("error");
        if (!$dbSchedule->tagValidateRules($dbSchedule->tag))
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
        if ($dbSchedule->where(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid))->delete())
            exit("true");
        else
            exit("false");
    }


    /**
     * 查询一天日程
     * @param int date 选择的日期，精确到日,格式如Y-m-d
     * @return error "" 非法操作
     * @return array[ReturnJson] 一个ReturnJson数组，每一项是一个日程
     * @return null 当天没有日程
     */
    public function day()
    {
        $dbSchedule     =   D("Schedule");

        $date   =   I("param.date","");
        $dbSchedule->dateValidateRules($date);

        $map["startTime"]   =   array("between",array($date." 00:00:00",$date." 23:59:59"));
        $data   =   null;
        $data   =   $dbSchedule->where($map)->where(array("uid"=>$this->uid))->select();
        
        $this->ajaxReturn($this->trimForAjax($data));
    }



    /**
     * 查询一周日程
     * @param int date 选择的日期，精确到日,格式如Y-m-d
     * @return error "" 非法操作
     * @return array[ReturnJson] 一个ReturnJson数组，每一项是一个日程
     * @return null 当周没有日程
     */
    public function week()
    {
        $dbSchedule     =   D("Schedule");

        $date   =   I("param.date","");
        $dbSchedule->dateValidateRules($date);

        if (date("N",strtotime($date)) == 7)
            $startDate =   strtotime("Sunday",strtotime($date));
        else
            $startDate =   strtotime("last Sunday",strtotime($date));
        $endDate   =   strtotime("Saturday",strtotime($date));

        $map["startTime"]   =   array("between",array(date("Y-m-d",$startDate)." 00:00:00",date("Y-m-d",$endDate)." 23:59:59"));
        $data   =   null;
        $data   =   $dbSchedule->where($map)->where(array("uid"=>$this->uid))->select();
        
        $this->ajaxReturn($this->trimForAjax($data));
    }



    /**
     * 获取指定日期所在月的开始日期与结束日期
     * @param  string $date 选择的日期，格式如Y-m-d
     * @return array["sdate"] 开始时间
     * @return array["edate"] 结束时间
     */
    private function getMonthRange($date){
        $ret    =   array();
        $timestamp  =    strtotime($date);
        $mdays=date('t',$timestamp);
        $ret['sdate']=date('Y-m-1 00:00:00',$timestamp);
        $ret['edate']=date('Y-m-'.$mdays.' 23:59:59',$timestamp);
        return $ret;
    }


    /**
     * 查询一月日程
     * @param int date 选择的日期，精确到日,格式如Y-m-d
     * @return error "" 非法操作
     * @return array[ReturnJson] 一个ReturnJson数组，每一项是一个日程
     * @return null 当月没有日程
     */
    public function month()
    {
        $dbSchedule     =   D("Schedule");

        $date   =   I("param.date","");
        $dbSchedule->dateValidateRules($date);

        $pairDate  =   $this->getMonthRange($date);

        $map["startTime"]   =   array("between",array($pairDate["sdate"],$pairDate["edate"]));
        $data   =   null;
        $data   =   $dbSchedule->where($map)->where(array("uid"=>$this->uid))->select();
        $this->ajaxReturn($this->trimForAjax($data));
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

        $tmp    =   M("Schedule")->where(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid))->find();
        $tmp["check"]   =   json_decode($tmp["check"],true);
        if ($tmp["check"] === null)
            $tmp["check"] = array(array("content"=>$data["content"],"state"=>$data["state"]));
        else
            array_push($tmp["check"],array("content"=>$data["content"],"state"=>$data["state"]));
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["check"]);

        $tmp3   =   $dbSchedule->save(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid,"check"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
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

        $tmp    =   $dbSchedule->save(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid,"check"=>$check));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 给当前用户的当前事项添加一个参与者
     * @param int sid
     * @param int uid 被添加的用户
     * @return bool "" 是否成功
     */
    public function addParticipant()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid,uid")->create(I('param.'));
        $newUid     =   (int)$dbSchedule->uid;//TODO:去除重复uid的检查
        $tmp    =   M("Schedule")->where(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid))->find();
        $tmp["participant"]   =   json_decode($tmp["participant"],true);
        if ($tmp["participant"] === null)
            $tmp["participant"] = array($newUid);
        else
            array_push($tmp["participant"],$newUid);
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["participant"]);

        $tmp3   =   $dbSchedule->save(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid,"participant"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 当前用户的当前事项更新参与者列表
     * @param int sid
     * @param ReturnJson_participant participant 全部的参与者列表
     * @return bool "" 是否成功
     * @return error "" 出错
     */
    public function editParticipant()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));
        $dbSchedule->participant = I('param.participant',"null",false);
        if (!$dbSchedule->participantValidateRules($dbSchedule->participant))
            exit("error");

        $tmp    =   $dbSchedule->save(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid,"participant"=>$dbSchedule->participant));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 给当前用户的当前事项添加一个标签
     * @param int sid
     * @param string tag 新增的标签
     * @return bool "" 是否成功
     */
    public function addTag()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));
        $data["tag"]      =   I("param.tag",null);

        if (!$dbSchedule->tagValidateRules($data))//TODO:去除重复tag的检查
            exit("error");

        
        $tmp    =   M("Schedule")->where(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid))->find();
        $tmp["tag"]   =   json_decode($tmp["tag"],true);
        if ($tmp["tag"] === null)
            $tmp["tag"] = array($data["tag"]);
        else
            array_push($tmp["tag"],$data["tag"]);
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["tag"]);

        $tmp3   =   $dbSchedule->save(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid,"tag"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 当前用户的当前事项修改标签
     * @param int sid
     * @param ReturnJson_tag tag 全部的标签列表
     * @return bool "" 是否成功
     * @return error "" 出错
     */
    public function editTag()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));
        $dbSchedule->tag = I('param.tag',"null",false);
        if (!$dbSchedule->tagValidateRules($dbSchedule->tag))
            exit("error");

        $tmp    =   $dbSchedule->save(array("uid"=>$this->uid,"sid"=>$dbSchedule->sid,"tag"=>$dbSchedule->tag));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 更改日程的状态
     * @param int sid
     * @param int state 日程状态
     * @return bool "" 是否完成
     */
    public function editState()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid,state")->create(I('param.'));

        $tmp    =   $dbSchedule->save();
        if ( ($tmp === false) || ($tmp === null) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 给当前用户的当前日程添加一个评论
     * @param int sid
     * @param int uid 谁发表的评论
     * @param string content 内容
     * @return bool "" 是否成功
     */
    public function addComment()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid,content,uid")->create(I('param.'));
        $data["date"]      =   date("Y-m-d H:i:s");

        $tmp    =   M("Schedule")->where(array("sid"=>$dbSchedule->sid))->find();
        $tmp["comment"]   =   json_decode($tmp["comment"],true);
        if ($tmp["comment"] === null)
            $tmp["comment"] = array(array("content"=>$dbSchedule->content,"date"=>$data["date"],"uid"=>$dbSchedule->uid));
        else
            array_push($tmp["comment"],array("content"=>$dbSchedule->content,"date"=>$data["date"],"uid"=>$dbSchedule->uid));
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["comment"]);

        $tmp3   =   $dbSchedule->save(array("sid"=>$dbSchedule->sid,"comment"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 修改全部评论，注意是全部，因为区分不出来修改第几个评论
     * @param int sid
     * @param ReturJson_comment comment 全部评论的json
     * @return bool "" 是否成功
     * @return error "" 错误
     */
    public function editComment()
    {
        $dbSchedule     =   D("Schedule");

        $dbSchedule->field("sid")->create(I('param.'));
        $comment = I('param.comment',"null",false);
        if (!$dbSchedule->commentValidateRules($check))
            exit("error");

        $tmp    =   $dbSchedule->save(array("sid"=>$dbSchedule->sid,"comment"=>$comment));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }

}