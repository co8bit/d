/**
 * Created by Administrator on 2014/12/29.
 */
$(document).ready(function(){
    var apiBaseurl='../server/index.php?';
    function geturl(api,m,c,a){
        return api+'m='+m+'&c='+c+'&a='+a;
    }
    //赋值
    //
    var str=window.location.href;
    var aid=(str.split('='))[1];
    function gaihuilai(str){
        str=str.replace(/&lt;/g,'<');
        str=str.replace(/&gt;/g,'>');
        str=str.replace(/&quot;/g,'');
        return str;
    }
    $.datepicker.regional['zh-CN'] = {
        clearText: '清除',
        clearStatus: '清除已选日期',
        closeText: '关闭',
        closeStatus: '不改变当前选择',
        prevText: '<上月',
        prevStatus: '显示上月',
        prevBigText: '<<',
        prevBigStatus: '显示上一年',
        nextText: '下月>',
        nextStatus: '显示下月',
        nextBigText: '>>',
        nextBigStatus: '显示下一年',
        currentText: '今天',
        currentStatus: '显示本月',
        monthNames: ['一月','二月','三月','四月','五月','六月', '七月','八月','九月','十月','十一月','十二月'],
        monthNamesShort: ['一','二','三','四','五','六', '七','八','九','十','十一','十二'],
        monthStatus: '选择月份',
        yearStatus: '选择年份',
        weekHeader: '周',
        weekStatus: '年内周次',
        dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
        dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
        dayNamesMin: ['日','一','二','三','四','五','六'],
        dayStatus: '设置 DD 为一周起始',
        dateStatus: '选择 m月 d日, DD',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        initStatus: '请选择日期',
        isRTL: false};
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
    $("input[name='startTime']").datepicker({changeMonth: true,changeYear: true});
    $("input[name='endTime']").datepicker({minDate:new Date($("input[name='startTime']").val()),changeMonth: true,changeYear: true});
    $("input[name='logoPic']").change(function(){
        var str=($(this).val().split('\\'))[($(this).val().split('\\')).length-1];
        if(str.length>=12){
            str=str.substr(0,12)+'...'
        }
        $('.file-content').html(str);
        $("input[name='mode']").val('2');
    })
    $('#editor').css({'height':'400px','width':'700px','display':'inline-block','vertical-align':'top'});
    ue = UE.getEditor('editor',{
        toolbars:[['bold','italic','underline','strikethrough','forecolor','insertorderedlist','insertunorderedlist','fontsize','simpleupload','source']],
        autoHeightEnabled:true,
        autoFloatEnabled: true,
        enableAutoSave:true
    });
    ue.ready(function(){
        $.post(geturl(apiBaseurl,'Home','Activity','queryOne'),{aid:aid},function(data){
            $('input[name="title"]').val(data.title);
            $('input[name="aid"]').val(aid);
            $('input[name="location"]').val(data.location);
            $('input[name="startTime"]').val(data.startTime);
            $('input[name="endTime"]').val(data.endTime);
            $('input[name="content"]').val(data.content);
            $('input[name="participant"]').val(data.participant);
            $('input[name="title"]').val(data.title);
            $('input[name="brief"]').val(data.brief);
            $('input[name="participant"]').val(JSON.stringify(data.participant));
            $('select').find('[value="'+data.class+'"]').attr('selected','true');
            $('.file-content').html((data.logoPic.split('/'))[(data.logoPic.split('/')).length-1])
            ue.setContent(gaihuilai(data.content));
        })
    })
    setInterval(function(){
        var message=$(document.getElementById('iframe').contentWindow.document.body).html();
        if(message.indexOf('true')!=-1){
            alert('修改成功');
            setTimeout(function(){
                window.location.href='index.html';
            })
        }
    },1000);
    $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(uid){
        $.post(geturl(apiBaseurl,'Home','User','getUserInfo'),{uid:uid},function(data){
            var uidarray=new Array();
            uidarray[0]=uid;
            if(data.logoPic==''){
                $('.participant-img img').attr('src','image/oneday-weishangchuan.png');
            }else{
                $('.participant-img img').attr('src','../server/Public'+data.logoPic);
            }
            $('.participant input[name="participant"]').val(JSON.stringify(uidarray));
        })
    })
    $('.ui-datepicker').css('z-index',100);
})