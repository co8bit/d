<?php
namespace Home\Controller;
use Think\Controller;

class ActivityController extends Controller
{
	protected $uid = null;

	protected function _initialize()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->uid		=		session("uid");
        empty($this->uid) && $this->error("error",U("Index/login"));
    }

    /**
     * 查询未完成的活动;
     * @param int page 当前的页数
     * @return json 活动内容，形如：
     *         {
     *             "pageTotalNum":2,//总页数
     *             "content":[ //具体的内容
     *                 {},//一个活动
     *                 {}
     *             ]
     *         }
     */
    public function queryActivity()
    {
        $dbActivity     =   D("Activity");

        $page   =   I("param.page",1);

        $scheduleCount = $dbActivity->where(array("class"=>1,"state"=>0))->count();
        $scheduleTotalPageNum   =   ceil($scheduleCount / _ACTIVITY_PAGE_NUM);

        if ($page > $scheduleTotalPageNum)
            exit("error");

        $resule     =   $dbActivity->where(array("class"=>1,"state"=>0))->order("startTime")->limit(($page-1) * _ACTIVITY_PAGE_NUM,_ACTIVITY_PAGE_NUM)->select();

        $tmp    =   null;
        $tmp["pageTotalNum"] = $scheduleTotalPageNum;
        $tmp["content"]    =   $this->trimForAjax($resule);
        $this->ajaxReturn($tmp);
    }

    /**
     * 更改活动的状态
     * @param int sid
     * @param int state 状态
     * @return bool "" 是否完成
     */
    public function editState()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("sid,state")->create(I('param.'));

        $tmp    =   $dbActivity->save();
        if ( ($tmp === false) || ($tmp === null) )
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
        $dbActivity     =   D("Activity");

        $dbActivity->field("sid,uid")->create(I('param.'));
        $newUid     =   (int)$dbActivity->uid;//TODO:去除重复uid的检查
        $tmp    =   $dbActivity->where(array("uid"=>$this->uid,"sid"=>$dbActivity->sid))->find();
        $tmp["participant"]   =   json_decode($tmp["participant"],true);
        if ($tmp["participant"] === null)
            $tmp["participant"] = array($newUid);
        else
            array_push($tmp["participant"],$newUid);
        $tmp2   =   nnull;
        $tmp2   =   json_encode($tmp["participant"]);

        $tmp3   =   $dbActivity->save(array("uid"=>$this->uid,"sid"=>$dbActivity->sid,"participant"=>$tmp2));
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
        $dbActivity     =   D("Activity");

        $dbActivity->field("sid")->create(I('param.'));
        $dbActivity->participant = I('param.participant',"null",false);
        if (!$dbActivity->participantValidateRules($dbActivity->participant))
            exit("error");

        $tmp    =   $dbActivity->save(array("uid"=>$this->uid,"sid"=>$dbActivity->sid,"participant"=>$dbActivity->participant));
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
        $dbActivity     =   D("Activity");

        $dbActivity->field("sid")->create(I('param.'));
        $data["tag"]      =   I("param.tag",null);

        if (!$dbActivity->tagValidateRules($data))//TODO:去除重复tag的检查
            exit("error");

        
        $tmp    =   $dbActivity->where(array("uid"=>$this->uid,"sid"=>$dbActivity->sid))->find();
        $tmp["tag"]   =   json_decode($tmp["tag"],true);
        if ($tmp["tag"] === null)
            $tmp["tag"] = array($data["tag"]);
        else
            array_push($tmp["tag"],$data["tag"]);
        $tmp2   =   nnull;
        $tmp2   =   json_encode($tmp["tag"]);

        $tmp3   =   $dbActivity->save(array("uid"=>$this->uid,"sid"=>$dbActivity->sid,"tag"=>$tmp2));
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
        $dbActivity     =   D("Activity");

        $dbActivity->field("sid")->create(I('param.'));
        $dbActivity->tag = I('param.tag',"null",false);
        if (!$dbActivity->tagValidateRules($dbActivity->tag))
            exit("error");

        $tmp    =   $dbActivity->save(array("uid"=>$this->uid,"sid"=>$dbActivity->sid,"tag"=>$dbActivity->tag));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }

}