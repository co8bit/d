/**
 * Created by Administrator on 2015/1/2.
 */
$(document).ready(function(){
    function getLogopicSrc(logoPic){
        if(logoPic!=''){
            return '../server/Public'+logoPic;
        }else{
            return 'image/oneday-weishangchuan.png';
        }
    }
    var str=window.location.href;
    var aid=(str.split('='))[1];
    var apiBaseurl='../server/index.php?';
    function geturl(api,m,c,a){
        return api+'m='+m+'&c='+c+'&a='+a;
    }
    $.post(geturl(apiBaseurl,'Home','Activity','queryActivityUser'),{aid:aid},function(data){
        console.log(data);
        if(data=='error'){
            alert('您没有权限查看该活动名单');
        }else{
            for(var i=0;i<data.length;i++){
                $('.item-container').append('<div class="item">'+
                    '<div class="item-img">'+
                    '<img src="'+getLogopicSrc(data[i].logoPic)+'" width="45px" height="45px"/>'+
                    '</div>'+
                '<div class="item-name">'+
                    '<p>账号：'+data[i].name+'</p>'+
                    '<p>名字：'+data[i].realName+'</p>'+
                    '<p>电话：'+data[i].phone+'</p>'+
                '</div>'+
                '</div>')
            }
        }
    })
    $('.namelist-button span').click(function(){
        window.open(geturl(apiBaseurl,'Home','Activity','createActivityUserExcel')+'&aid='+aid);
    })
})
