<?php
namespace Home\Model;
use Think\Model;

class ScheduleModel extends Model
{
	// 自动验证设置
	// protected $_validate = array(
	// 		array('userName', 'require', '用户名不能为空！'),
	// 		array('userName','','用户名已经存在！',0,'unique',Model::MODEL_BOTH), //验证name字段是否唯一
	// 		array('userPassword', 'require', '密码不能为空！', 0),
	// 		array('userPassword2', 'require', '请输入第二遍密码', 0),
	// 		array('userPassword','userPassword2','两次输入的密码不一样',0,'confirm',Model::MODEL_BOTH), // 验证确认密码是否和密码一致
	// );

	public function tagValidateRules()
	{
		return true;
	}

	public function checkValidateRules()
	{
		return true;
	}

	public function participantValidateRules()
	{
		return true;
	}

}
?>