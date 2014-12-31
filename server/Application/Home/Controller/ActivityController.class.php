<?php
namespace Home\Controller;
use Think\Controller;

include(APP_PATH."/Home/Conf/MyConfigINI.php");

class ActivityController extends Controller
{
    /**
     * 上传文件的配置数组
     * @var array
     */
    protected $UPLOADCONFIG = array(    
        'maxSize'    =>    3145728,
        'rootPath'   =>    './Public/',
        'savePath'   =>    '/Uploads/',    
        'saveName'   =>    array('uniqid',''),    
        'exts'       =>    array('jpg', 'png', 'jpeg'),    
        'autoSub'    =>    true,    
        'subName'    =>    array('date','Ymd'),
    );



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
    * 查询某个活动
    * @param int aid
    * @return error 错误
    * @return ReturnJson 返回数据
    */
    public function queryOne()
    {
        $dbActivity = D("Activity");

        $dbActivity->field("aid")->create(I('param.'));

        $result    =   $dbActivity->where(array("aid"=>$dbActivity->aid))->find();
        $result["tag"] = json_decode($result["tag"],true);
        $result["participant"] = json_decode($result["participant"],true);
        $this->ajaxReturn($result);
    }

    /**
     * 创建一个活动
     * @param 多个参数，名称参考returnJson,但是形式是post
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function create()
    {
        $dbActivity     =   D("Activity");
        $data   =   null;

        $dbActivity->field("title,location,startTime,endTime,content,brief,templateNo,class")->create(I('param.'));
        $dbActivity->uid =  $this->uid;
        $dbActivity->tag = I('param.tag',"null",false);
        $dbActivity->participant = I('param.participant',"null",false);
        $dbActivity->state = 0;

        //TODO:自动补全和验证
        if (!$dbActivity->tagValidateRules($dbActivity->tag))
            exit("error");
        if (!$dbActivity->participantValidateRules($dbActivity->participant))
            exit("error");

        $upload = new \Think\Upload($this->UPLOADCONFIG);// 实例化上传类
        $info   =   $upload->upload();
        if(!$info)
        {// 上传错误提示错误信息
            exit($upload->getError());
        }
        else
        {// 上传成功获取上传文件信息    
            // dump($info);
            $dbActivity->logoPic = $info["logoPic"]['savepath'].$info["logoPic"]['savename'];
        }


        $tmp    =   $dbActivity->add();
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
     * 修改一个活动
     * @param 多个参数，名称参考returnJson,但是形式是post; 
     * @param int mode 模式，为1修改活动（不改logo），为2是修改活动（改logo）
     * @return true "" 成功
     *         其他任何东西 "" 失败
     *         error "" 非法操作
     */
    public function edit()
    {
        $dbActivity     =   D("Activity");
        $data   =   null;

        $dbActivity->field("aid,title,location,startTime,endTime,content,state,brief,templateNo,class")->create(I('param.'));
        $dbActivity->uid =  $this->uid;
        $dbActivity->tag = I('param.tag',"null",false);
        $dbActivity->participant = I('param.participant',"null",false);
        
        $mode   =   I("param.mode",0);
        if ((int)$mode == 2)
        {
            $upload = new \Think\Upload($this->UPLOADCONFIG);// 实例化上传类
            $info   =   $upload->upload();
            if(!$info)
            {// 上传错误提示错误信息
                exit($upload->getError());
            }
            else
            {// 上传成功获取上传文件信息    
                // dump($info);
                $dbActivity->logoPic = $info["logoPic"]['savepath'].$info["logoPic"]['savename'];
            }
        }

        if (!$dbActivity->tagValidateRules($dbActivity->tag))
            exit("error");
        if (!$dbActivity->participantValidateRules($dbActivity->participant))
            exit("error");

        $tmp    =   null;
        $tmp = $dbActivity->save();
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
     * 删除一个活动
     * @param int aid
     * @return bool "" 是否成功
     */
    public function delete()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        if ($dbActivity->where(array("uid"=>$this->uid,"aid"=>$dbActivity->aid))->delete())
            exit("true");
        else
            exit("false");
    }


    /**
     * 查询所有未完成的活动;
     * @param int page 当前的页数
     * @param int class 活动的类别;class=9999,返回所有类别的活动
     * @return error "" page过大或没有此类活动
     * @return json 活动内容，形如：
     *         {
     *             "pageTotalNum":2,//总页数
     *             "content":[ //具体的内容
     *                 {},//一个活动
     *                 {}
     *             ]
     *         }
     */
    public function queryAll()
    {
        $dbActivity     =   D("Activity");

        $page   =   I("param.page",1);
        $class  =   I("param.class",4);
        if ($class == 9999)
        {
            $condition  =   "state=0 and (class=2 or class=3 or class=4)";
        }
        else
        {
            $condition  =   array("class"=>$class,"state"=>0);
        }

        $scheduleCount = $dbActivity->where($condition)->count();
        $scheduleTotalPageNum   =   ceil($scheduleCount / _ACTIVITY_PAGE_NUM);

        if ($page > $scheduleTotalPageNum)
            exit("error");

        $resule     =   $dbActivity->where($condition)->order("startTime")->limit(($page-1) * _ACTIVITY_PAGE_NUM,_ACTIVITY_PAGE_NUM)->select();

        $tmp    =   null;
        $tmp["pageTotalNum"] = $scheduleTotalPageNum;
        $tmp["content"]    =   $this->trimForAjax($resule);
        $this->ajaxReturn($tmp);
    }



    /**
     * 更改活动的状态
     * @param int aid
     * @param int state 状态
     * @return bool "" 是否完成
     */
    public function editState()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid,state")->create(I('param.'));
        $dbActivity->uid    =   $this->uid;

        $tmp    =   $dbActivity->save();
        if ( ($tmp === false) || ($tmp === null) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 给当前用户的当前活动添加一个参与者
     * @param int mode 改字段不用传入，是服务器代码自己用，不对外开放的字段
     *     当外部调用时（post）,mode=0：作为组织者，即调用者的uid要为活动创建人的uid
     *     当内部调用时（服务器端自己）,mode=1：任意uid
     * @param int aid
     * @param int uid 被添加的用户
     * @return bool "" 是否成功
     */
    public function addParticipant($mode = 0,$aid = 0,$uid = 0)
    {
        $dbActivity     =   D("Activity");

        if ($mode == 0)//post
        {
            $dbActivity->field("aid,uid")->create(I('param.'));

            $tmp    =   null;
            $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();
            if ((int)$this->uid != $tmp["uid"])
                exit("error");
        }
        elseif ($mode == 1)//php
        {
            $dbActivity->aid    =   $aid;
            $dbActivity->uid    =   $uid;
        }
        $newUid     =   (int)$dbActivity->uid;//TODO:去除重复uid的检查
        $tmp    =   null;
        $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();
        $tmp["participant"]   =   json_decode($tmp["participant"],true);
        if ($tmp["participant"] === null)
            $tmp["participant"] = array($newUid);
        else
            array_push($tmp["participant"],$newUid);
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["participant"]);

        $tmp3   =   $dbActivity->save(array("aid"=>$dbActivity->aid,"participant"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 当前用户的当前活动更新参与者列表（作为组织者进行）
     * @param int aid
     * @param ReturnJson_participant participant 全部的参与者列表
     * @return bool "" 是否成功
     * @return error "" 出错
     */
    public function editParticipant()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $dbActivity->participant = I('param.participant',"null",false);
        if (!$dbActivity->participantValidateRules($dbActivity->participant))
            exit("error");

        $tmp    =   $dbActivity->save(array("uid"=>$this->uid,"aid"=>$dbActivity->aid,"participant"=>$dbActivity->participant));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 给当前用户的当前活动添加一个标签（作为组织者进行）
     * @param int aid
     * @param string tag 新增的标签
     * @return bool "" 是否成功
     */
    public function addTag()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $data["tag"]      =   I("param.tag",null);

        if (!$dbActivity->tagValidateRules($data))//TODO:去除重复tag的检查
            exit("error");

        
        $tmp    =   M("Activity")->where(array("uid"=>$this->uid,"aid"=>$dbActivity->aid))->find();
        $tmp["tag"]   =   json_decode($tmp["tag"],true);
        if ($tmp["tag"] === null)
            $tmp["tag"] = array($data["tag"]);
        else
            array_push($tmp["tag"],$data["tag"]);
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["tag"]);

        $tmp3   =   $dbActivity->save(array("uid"=>$this->uid,"aid"=>$dbActivity->aid,"tag"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 当前用户的当前活动修改标签（作为组织者进行）
     * @param int aid
     * @param ReturnJson_tag tag 全部的标签列表
     * @return bool "" 是否成功
     * @return error "" 出错
     */
    public function editTag()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $dbActivity->tag = I('param.tag',"null",false);
        if (!$dbActivity->tagValidateRules($dbActivity->tag))
            exit("error");

        $tmp    =   $dbActivity->save(array("uid"=>$this->uid,"aid"=>$dbActivity->aid,"tag"=>$dbActivity->tag));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 给当前用户的当前活动添加一个评论
     * @param int aid
     * @param string content 内容
     * @return bool "" 是否成功
     */
    public function addComment()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid,content")->create(I('param.'));
        $data["date"]      =   date("Y-m-d H:i:s");

        $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();
        $tmp["comment"]   =   json_decode($tmp["comment"],true);
        if ($tmp["comment"] === null)
            $tmp["comment"] = array(array("content"=>$dbActivity->content,"date"=>$data["date"]));
        else
            array_push($tmp["comment"],array("content"=>$dbActivity->content,"date"=>$data["date"]));
        $tmp2   =   null;
        $tmp2   =   json_encode($tmp["comment"]);

        $tmp3   =   $dbActivity->save(array("aid"=>$dbActivity->aid,"comment"=>$tmp2));
        if ( ($tmp3 === null) || ($tmp3 === false) )
            exit("false");
        else
            exit("true");
    }


    /**
     * 修改全部评论，注意是全部，因为区分不出来修改第几个评论
     * @param int aid
     * @param ReturJson_comment comment 全部评论的json
     * @return bool "" 是否成功
     * @return error "" 错误
     */
    public function editComment()
    {
        $dbActivity     =   D("Activity");

        $dbActivity->field("aid")->create(I('param.'));
        $comment = I('param.comment',"null",false);
        if (!$dbActivity->commentValidateRules($check))
            exit("error");

        $tmp    =   $dbActivity->save(array("aid"=>$dbActivity->aid,"comment"=>$comment));
        if ( ($tmp === null) || ($tmp === false) )
            exit("false");
        else
            exit("true");
    }



    /**
     * 添加活动到日程中去，完成两件事情：
     *     1.添加aid活动的参与者
     *     2.添加活动到uid日程中去
     * @param int uid 将活动添加到uid那个人的日程中
     * @param int aid 哪个活动
     * @return bool "" 是否成功
     */
    public function addActivityToSchedule()
    {
        $dbActivity     =   D("Activity");
        $dbSchedule     =   D("Schedule");

        $dbActivity->field("uid,aid")->create(I("param."));//TODO:是否uid已有aid的检查
        
        $tmp    =   M("Activity")->where(array("aid"=>$dbActivity->aid))->find();

        $data   =   $tmp;
        unset($data["uid"]);
        unset($data["content"]);
        unset($data["participant"]);
        unset($data["comment"]);
        unset($data["templateNo"]);
        unset($data["brief"]);
        unset($data["heat"]);
        $data["uid"]    =   $this->uid;
        $data["content"]  =   $tmp["brief"];

        if (!$dbSchedule->add($data))
            exit("false");

        //TODO:一致性？
        $this->addParticipant(1,$dbActivity->aid,$dbActivity->uid);
        $ScheduleAction  =   A("Schedule");
        $ScheduleAction->create();
    }

}