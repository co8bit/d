<?php
require_once(APP_PATH."/Home/Conf/MyConfigINI.php");

/**
 * 发短信函数
 * @param  string  $param      短信内容，多个参数逗号隔开
 * @param  int     $to         要发送到的手机号码
 * @param  string  $templateId 短信模板号
 */
function sendSMS($param = null, $to = 15355494740, $templateId = "2493")
{
    if (empty($param))
        exit("error");

    require_once(COMMON_PATH."/Common/ucpaas/lib/Ucpaas.class.php");

    $options['accountsid']  =   _UCPASS_ACCOUNTSID;
    $options['token']       =   _UCPASS_TOKEN;
    $ucpass                 =   new \Ucpaas($options);
    $appId                  =   _UCPASS_APPID;

    $returnData		=	 $ucpass->templateSMS($appId,$to,$templateId,$param);
}

?>