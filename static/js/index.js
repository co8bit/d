/**
 * Created by Administrator on 2014/12/8.
 */

$(document).ready(function(){
    //恢复默认
    $('body').click(function(){
        $.each($('.reverge-tag'),function(i,d){
            $(d).css('background','url("image/'+$(d).attr('id')+'.jpg")');
        })
        $('.column1-tag').css('background','#fff').css('color','#999999').css('font-weight','normal').find('img').attr('src','image/oneday-rightarrow.png');
    })
    $('body').on('change','#column3-bottom-setting-file',function(){
        var file=$(this)[0].files[0];
        var thisobj=$(this);
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(e){
            thisobj.parent().find('.column3-bottom-setting-logoPic-zhezhu img').attr('src',this.result);
        }
    })
    var timeout;
    var timeinter;
    var timenow=new Date();
    var monthtimenow=new Date;
    var itemarray=new Array();
    var activityarray=new Array();
    var apiBaseurl='../server/index.php?';
    var pagenow=1;
    var userinfourl='../server/index.php?m=Home&c=User&a=editUserInfo';
    function geturl(api,m,c,a){
        return api+'m='+m+'&c='+c+'&a='+a;
    }
    $("input[name='endtime']").datetimepicker();
    $("input[name='starttime']").datetimepicker();
    var UEDITORFLAG=false;
    var ue;
    var globelmonthobj={addDate:function(str){
        if(this[str]){
            this[str]++;
        }else{
            this[str]=1;
        }
    }};
    var globelweekobj={addDate:function(str){
        if(this[str]){
            this[str]++;
        }else{
            this[str]=1;
        }
    }};
    changetime(timenow);
    jiazai();
    //用户信息载入
    userInfo();
    //得到图片路径
    function getLogopicSrc(logoPic){
        if(logoPic!=''){
            return '../server/Public'+logoPic;
        }else{
            return 'image/oneday-weishangchuan.png';
        }
    }
    function userInfo(){
        $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(uid){
            $.post(geturl(apiBaseurl,'Home','User','getUserInfo'),{uid:uid},function(data){
                if(data.logoPic){
                    src='../server/Public'+data.logoPic;
                }else{
                    src='image/oneday-weishangchuan.png';
                }
                if(data.realName){
                    var realname=data.realName;
                }else{
                    var realname='还没有名字'
                }
                $('.column1-search img').attr('src',src);
                $('.column1-p p:first').html(realname);
                $('.column1-p p:last').html(data.name);
                $('.column1-search img').css('height',$('.column1-search img').css('width')).css('border-radius','50%');
            })
        })

    }
    //日程完成
    $('body').on('click','.column3-bottom-taskdetail-header .confirm',function(){
        var sid=$('.column3-bottom').attr('sid');
        $.post(geturl(apiBaseurl,'Home','Schedule','editState'),{sid:sid,state:1},function(data){
            console.log(data);
            if(data=='true'){
                $('.column3-bottom-taskdetail-header .confirm').attr('src','image/oneday-confirm-button-on.png');
                setTimeout(jiazai,500);
                setTimeout(drawc3rili,500);
            }
        })
    })
    //设置
    $('#oneday-setting').click(function(){
       $('.column3-setting-hover').css('display','block');
    })
    //
    $('body').on('change','#userinfo-file-input',function(){
        $('#file-value').val($(this).val());
        userinfourl='../server/index.php?m=Home&c=User&a=setUserInfo';
        $('.column2-bottom form').attr('action',userinfourl);
    })
    $('body').on('click','#column2-bottom-setting-userinfo-submit',function(){
        userinfourl='../server/index.php?m=Home&c=User&a=editUserInfo';
        setTimeout(function(){$('.column2-bottom form').attr('action',userinfourl);},1000);
        timeinter=setInterval(function(){
            var message=$(document.getElementById('userInfoiframe').contentWindow.document.body).html();
            if(message=='true'){
                alert('修改成功了哦');
                jiazai();
                clearInterval(timeinter);
            }
        },500)
    })
    $('.column3-setting-item').click(function(){
        switch($(this).html()){
            case '个人中心':
                $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(id){
                    $.post(geturl(apiBaseurl,'Home','User','getUserInfo'),{uid:id},function(data){
                        src=getLogopicSrc(data.logoPic);
                        $('.column2-bottom').html('<iframe name="bushuaxin" id="userInfoiframe" style="display: none"></iframe>'+
                            '<form target="bushuaxin" enctype="multipart/form-data" method="post" action="'+userinfourl+'">'+
                            '<input type="hidden" value="'+data.uid+'" name="uid"/>'+
                            '<input type="hidden" value="'+data.pwd+'" name="pwd"/>'+
                            '<div class="column2-bottom-setting-bg">'+
                            '<img src="image/oneday-setting-bg.png" width="100%"/>'+
                            '</div>'+
                            '<div class="column2-bottom-setting-userinfo">'+
                            '<div class="column2-bottom-setting-userinfo-touxiang left">'+
                            '<img src="'+src+'" width="100%"/>'+
                            '</div>'+
                            '<div class="column2-bottom-setting-userinfo-info left">'+
                            '<p>'+data.realName+'</p>'+
                            '<p>'+data.name+'</p>'+
                            '</div>'+
                            '<div class="column2-bottom-setting-userinfo-all clear">'+
                            '<div class="column2-bottom-setting-userinfo-all-name">'+
                            '真实姓名: <input type="text" name="realName" value="'+data.realName+'"/>'+
                            '</div>'+
                            '<div class="column2-bottom-setting-userinfo-all-name">'+
                            '我的头像:<input type="text" readonly id="file-value"/>'+
                            '<input type="file" name="logoPic" id="userinfo-file-input"/>'+
                            '<div class="userinfo-file-input-zhezhu">浏览</div>'+
                            '</div>'+
                            '<div class="column2-bottom-setting-userinfo-all-name">'+
                            '手&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;机: <input type="text" name="phone" value="'+data.phone+'"/>'+
                            '</div>'+
                            '<div class="column2-bottom-setting-userinfo-all-name">'+
                            '地&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;址: <input type="text" name="address" value="'+data.address+'"/>'+
                            '</div>'+
                            '<div class="column2-bottom-setting-userinfo-all-name">'+
                            '<input type="submit" id="column2-bottom-setting-userinfo-submit"/>'+
                            '</div>'+
                            '</div>'+
                            '</div>').css('background','#fff');
                        $('.column2-bottom-setting-userinfo-touxiang img').css('height',$('.column2-bottom-setting-userinfo-touxiang img').css('width')).css('border-radius','50%');
                    })
                })
                break;
            case '退出':
                $.post(geturl(apiBaseurl,'Home','User','logout'),{},function(data){
                    if(data=='true'){
                        var con=confirm('确认要退出吗?')
                        if(con){
                            window.location.href='login.html';
                        }
                    }
                })
                break;
            case '活动管理':
                break;
            case '关于我们':
                break;
        }
        $('.column3-setting-hover').css('display','none');
    })
    //这里测试
    $('#oneday-page').click(function(){
        if($('.column3-bottom').attr('state')=='detail'){
            $.post(geturl(apiBaseurl,'Home','Schedule','query'),{sid:$('.column3-bottom').attr('sid')},function(data){
                var time=dbtimetojsdate(data.startTime);
                var nexttime=dbtimetojsdate(data.endTime);
                $('.column3-bottom').html('<div class="column3-bottom-title">'+
                    '<div class="left"><img src="image/oneday-rightarrow.png"></div>'+
                    '正在创建新任务'+
                    '</div>'+
                    '<form id="newtask">'+
                    '<div class="column3-bottom-input">'+
                    '<label>标题</label><input type="text" name="title" placeholder="在此输入标题" value="'+data.title+'"/>'+
                    '</div>'+
                    '<div class="column3-bottom-input">'+
                    '<label>标签</label><input type="text"  name="tag"placeholder="添加标签" value="'+data.tag+'"/>'+
                    '</div>'+
                    '<div class="column3-bottom-input">'+
                    '<label>地点</label><input type="text"  name="destination" placeholder="在此输入地点" value="'+data.location+'"/>'+
                    '</div>'+
                    '<div class="column3-bottom-input">'+
                    '<label>始于</label><input type="text" name="starttime" value="'+formattimetoinput(time)+'"/>'+
                    '</div>'+
                    '<div class="column3-bottom-input">'+
                    '<label>止于</label><input type="text" name="endtime" value="'+formattimetoinput(nexttime)+'"/>'+
                    '</div>'+
                    '<div class="column3-bottom-input">'+
                    '<label>描述</label><textarea name="description">'+data.content+'</textarea>'+
                    '</div>'+
                    '<div class="column3-bottom-option">'+
                    '<div class="column3-bottom-option-label left">检查项</div>'+
                    '<div class="column3-bottom-group left">'+
                    '</div>'+
                    '<div class="column3-bottom-option-new">'+
                    '<div class="column3-bottom-option-input">'+
                    '<input type="text" placeholder="输入检查项内容" name="checkoption"/>'+
                    '</div>'+
                    '<div class="clear">'+
                    '<div class="column3-bottom-option-new-confirm left">添 加</div>'+
                    '<div class="column3-bottom-option-new-cancel left">取 消</div>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '</form>'+
                    '<div class="column3-bottom-person overflow">'+
                    '<div class="column3-bottom-person-label left">参与者</div>'+
                    '<div class="column3-bottom-person-img left">'+
                    '<img src="image/oneday-addperson.png" width="50px" height="50px"/>'+
                    '</div>'+
                    '<div id="addperson">'+
                    '</div>'+
                    '</div>'+
                    '<div class="column3-bottom-cancel left">取 消</div>'+
                    '<div class="column3-bottom-confirm left">确 认</div>');
                for(var i=0;i<data.check.length;i++){
                    if(data.check[i].state==1){
                        $('.column3-bottom-group').append(
                                '<div class="option">'+
                                '<label value="'+data.check[i].content+'"><input type="checkbox" name="check"/>'+data.check[i].content+'</label>'+
                                '</div>'
                        )
                    }else{
                        $('.column3-bottom-group').append(
                                '<div class="option">'+
                                '<label value="'+data.check[i].content+'"><input type="checkbox" name="check" checked="checked"/>'+data.check[i].content+'</label>'+
                                '</div>'
                        )
                    }
                }
            })

            $('.column2-bottom').css('background','url("image/oneday-dayview-bg.png")').css('background-size','100%');
        }
    })



    //增加按钮逻辑
    $('#oneday-add').click(function(){
        $('.column3-bottom').removeAttr('sid').attr('state','create');
        drawc3rili();
    })
    //格式化时间放到input中
    function formattimetoinput(date){
        return date.getFullYear()+'/'+(date.getMonth()+1)+'/'+date.getDate()+' '+checktime(date.getHours())+':'+checktime(date.getMinutes());
    }


    //删除按钮逻辑
    $('#oneday-rubbish').click(function(){
        if($('.column3-bottom').attr('sid')){
            var con=confirm('确定要删除这条活动吗？');
            if(con){
                $.post(geturl(apiBaseurl,'Home','Schedule','delete'),{sid:$('.column3-bottom').attr('sid')},function(data){
                    if(data=='true'){
                        jiazai();
                    }
                });
            }
        }else{
            alert('您还未选择日程');
        }
    })
    //月视图单元格点击事件
    $('body').on('click','.column2-bottom-calender-container td',function(){
        timenow=dbtimetojsdate($(this).attr('date'));
        jiazai();
        changetime(timenow);
    })
    //周视图单元格点击事件
    $('body').on('click','.column2-bottom-week-container .column2-bottom-week-container-item',function(){
        timenow=dbtimetojsdate(($(this).attr('date')).replace(/-/g,'/'));
        jiazai();
        changetime(timenow);
    })
    //颜色替换
    function fanzhuan(num,length){
        if((parseInt(num/length)%2)==0){
            return num%length-1;
        }else{
            return (length-num%length)-1;
        }
    }
    //月视图点击事件
    $('#oneday-calcu').click(function(){
        var timenow=new Date();
        drawcalcu();
        $.post(geturl(apiBaseurl,'Home','Schedule','month'),{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
            initMonth(data,timenow);
        });
    })
    //周视图点击事件
    $('#oneday-calender').click(function(){
        jiazaiweek();
    })
    //周视图加载
    function jiazaiweek(){
        drawweekdom();
        $.post(geturl(apiBaseurl,'Home','Schedule','week'),{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
            initweek(data);
        });
    }
    //画周视图dom
    function drawweekdom(){
        $('.column2-bottom').html('').attr('state','zhoushitu');
        $('.column2-bottom').append('<table class="column2-bottom-calender" rules=none cellspacing=0 align=center>'+
            '<tr class="column2-bottom-calender-title">'+
            '<th>周日</th>'+
            '<th>周一</th>'+
        '<th>周二</th>'+
        '<th>周三</th>'+
        '<th>周四</th>'+
        '<th>周五</th>'+
        '<th>周六</th>'+
        '</tr>'+
        '<tr class="column2-bottom-week-container">'+
            '<td></td>'+
            '<td></td>'+
            '<td></td>'+
            '<td></td>'+
            '<td></td>'+
            '<td></td>'+
            '<td></td>'+
        '</tr>'+
        '</table>')
        $('.column2-bottom-week-container td:even').css('background','#f9f9f9');
    }
    //周视图加载
    function initweek(data,timenow){
        var colorarray=[['#f3f2f0','#efece7','#e9e4de','#e0dcd1','#d4d0c5','#c2beb3'],['#f2f3ee','#edeee8','#e7e9dc','#e0e1d3','#d4d5c7','#c2c3b5']];
        var array=new Array();

        $.each(data,function(i,d){
            array.push(d);
        })
        array.sort(function(a,b){
            return parseInt(dbtimetojsdate(a.startTime).getTime())-parseInt(dbtimetojsdate(b.startTime).getTime());
        })
        var weekarray=array;
        for(var i=0;i<weekarray.length;i++){
            if(weekarray[i].title.length>=2){
                var title=weekarray[i].title.substr(0,2)+'...';
            }else{
                var title=weekarray[i].title
            }
            var a=new Date((weekarray[i].startTime.split(' '))[0].replace(/-/g,'/'));
            $('.column2-bottom-week-container').find('td:eq('+ a.getDay()+')').append('<div class="column2-bottom-week-container-item item-new" date="'+weekarray[i].startTime+'"><h2>'+title+'</h2></div>');
            $('.item-new').css('background',colorarray[a.getDay()%2][fanzhuan($('.item-new').parent().parent().find('td:eq('+ a.getDay()+')').find('.column2-bottom-week-container-item').length,colorarray[0].length)]);
            $('.item-new').removeClass('item-new');
            console.log(a);
        }
    }
    //更改时间显示
    function changetime(time){
        $('.column2-date-time').html((time.getDay()==0?'周日':'周'+time.getDay())+','+(time.getMonth()+1)+'月'+time.getDate()+'日');
    }
    //判断是否是闰年
    function isrunnian(year){
        if((year%400==0)||((year%4==0)&&(year%100!=0))){
            return true;
        }else{
            return false;
        }
    }
    //得到前一天
    function getpreviousday(year,month,date){
        //计算是否闰年
        var now=new Date(year+','+month+','+date);
        return new Date(now.getTime()-24*60*60*1000);
    }
    //得到后一天
    function getnextday(year,month,date){
        //计算是否闰年
        var now=new Date(year+','+month+','+date);
        return new Date(now.getTime()+24*60*60*1000);
    }
    //得到前一周
    function getpreviousweek(year,month,date){
        //计算是否闰年
        var now=new Date(year+','+month+','+date);
        return new Date(now.getTime()-7*24*60*60*1000);
    }
    //得到后一周
    function getnextweek(year,month,date){
        //计算是否闰年
        var now=new Date(year+','+month+','+date);
        return new Date(now.getTime()+7*24*60*60*1000);
    }
    //得到前一个月
    function getpreviousmonth(year,month,date){
        //计算是否闰年
        if(isrunnian(parseInt(year))){
            var day=[31,29,31,30,31,30,31,31,30,31,30,31]
        }else{
            var day=[31,28,31,30,31,30,31,31,30,31,30,31]
        }
        var now=new Date(year+','+month+','+date);
        return new Date(now.getTime()-day[(month-1)%12]*24*60*60*1000);
    }
    //得到后一个月
    function getnextmonth(year,month,date){
        //计算是否闰年
        if(isrunnian(parseInt(year))){
            var day=[31,29,31,30,31,30,31,31,30,31,30,31]
        }else{
            var day=[31,28,31,30,31,30,31,31,30,31,30,31]
        }
        var now=new Date(year+','+month+','+date);
        return new Date(now.getTime()+day[(month-1)%12]*24*60*60*1000);
    }

    //点击得到前
    $('#oneday-leftarrow').click(function(){
        if($('.column2-bottom').attr('state')=='yueshitu'){
            timenow=getpreviousmonth(timenow.getFullYear(),(timenow.getMonth()+1),timenow.getDate());
            drawcalcu();
            $.post(geturl(apiBaseurl,'Home','Schedule','month'),{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
                if(data){
                    initMonth(data,timenow);
                }
                else{

                }
            });
            changetime(timenow);
        }else if($('.column2-bottom').attr('state')=='rishitu'){
            timenow=getpreviousday(timenow.getFullYear(),(timenow.getMonth()+1),timenow.getDate());
            jiazai();
            changetime(timenow);
        }else if($('.column2-bottom').attr('state')=='zhoushitu'){
            timenow=getpreviousweek(timenow.getFullYear(),(timenow.getMonth()+1),timenow.getDate());
            jiazaiweek();
            changetime(timenow);
        }
    })
    //点击得到后
    $('#oneday-rightarrow').click(function(){
        if($('.column2-bottom').attr('state')=='yueshitu'){
            timenow=getnextmonth(timenow.getFullYear(),(timenow.getMonth()+1),timenow.getDate());
            drawcalcu();
            $.post(geturl(apiBaseurl,'Home','Schedule','month'),{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
                if(data){
                    initMonth(data,timenow);
                }else{

                }
            });
            changetime(timenow);
        }else if($('.column2-bottom').attr('state')=='rishitu'){
            timenow=getnextday(timenow.getFullYear(),(timenow.getMonth()+1),timenow.getDate());
            jiazai();
            changetime(timenow);
        }else if($('.column2-bottom').attr('state')=='zhoushitu'){
            timenow=getnextweek(timenow.getFullYear(),(timenow.getMonth()+1),timenow.getDate());
            jiazaiweek();
            changetime(timenow);
        }
    })
    //页面变换主逻辑
    $('.column1-tag').click(function(){
        switch ($(this).find('.column1-tag-label').html()){
            case '发现活动':
                jiazaiActivity(pagenow);
                $('.column3-bottom').html('<div class="column3-bottom-activity-title"><img src="image/oneday-rightarrow.png"/>最近活动</div>'+
                    '<div class="column3-bottom-activity-container">'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '</div>'+
                    '<div class="column3-bottom-activity-title"><img src="image/oneday-rightarrow.png"/>热门活动</div>'+
                    '<div class="column3-bottom-activity-container">'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '<div class="column3-bottom-activity-item">'+
                    '<img src="image/oneday-activity-img.jpg"/>'+
                    '<span>练练——别在冬天放弃自己</span>'+
                    '</div>'+
                    '</div>')
                $('.column2-bottom').css('background','#f8f7f6');
                break;
            case '日历':
                jiazai();
                drawc3rili();
                $('.column3-bottom').removeAttr('detail').removeAttr('sid');
                break;
        }
        $('.column1-tag').css('background','#fff').css('color','#999999').css('font-weight','normal').find('img').attr('src','image/oneday-rightarrow.png');
        $(this).css('background','#f4a8a3').css('color','#fff').css('font-weight','bold').find('img').attr('src','image/oneday-rightarrow-on.png');
        event.stopPropagation();
    })

    //活动单元点击逻辑
    $('body').on('click','.column2-bottom-activity-item',function(){
        var uid=$(this).attr('uid');
        var aid=$(this).attr('aid');
        $.post(geturl(apiBaseurl,'Home','Activity','queryOne'),{aid:aid},function(data){
            $.post(geturl(apiBaseurl,'Home','User','getUserInfo'),{uid:uid},function(userinfo){
                $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(id){
                    $('.column2-bottom').html('<div class="column2-bottom-activitydetail-top">'+
                        '<img src="image/oneday-activitydetail-bg.png" width="100%">'+
                        '<div class="column2-bottom-activitydetail-top-joinin">'+
                        '<img src="image/oneday-join-in.png" width="100%"/>'+
                        '</div>'+
                        '</div>'+
                        '<div class="column2-bottom-activitydetail-author">'+
                        '<div class="column2-bottom-activitydetail-author-touxiang left">'+
                        '<img src="image/touxiang.jpg" width="100%"/>'+
                        '</div>'+
                        '<div class="column2-bottom-activitydetail-author-title left">'+
                        '<h1>'+data.title+'</h1>'+
                        '<h2>作者：'+userinfo.name+'&nbsp;&nbsp;|&nbsp;&nbsp;'+data.startTime+'</h2>'+
                        '</div>'+
                        '</div>'+
                        '<div class="column2-bottom-activitydetail-content">'+
                        '</div>'+
                        '<div class="column2-bottom-activitydetail-participant">'+
                        '<div class="left column2-bottom-activitydetail-participant-circle">'+
                        '<img src="image/oneday-activity-circle.png" width="100%"/>'+
                        '</div>'+
                        /*'<div class="left column2-bottom-activitydetail-participant-item">'+
                        '<img src="image/oneday-activity-circle.png" width="100%"/>'+
                        '</div>'+
                        '<div class="left column2-bottom-activitydetail-participant-item">'+
                        '<img src="image/oneday-activity-circle.png" width="100%"/>'+
                        '</div>'+
                        '<div class="left column2-bottom-activitydetail-participant-item">'+
                        '<img src="image/oneday-activity-circle.png" width="100%"/>'+
                        '</div>'+
                        '<div class="left column2-bottom-activitydetail-participant-item">'+
                        '<img src="image/oneday-activity-circle.png" width="100%"/>'+
                        '</div>'+*/
                        '</div>'+
                        '<div class="column2-bottom-activitydetail-buttons">'+
                        '<div class="column2-bottom-activitydetail-button left"><img src="image/oneday-activity-zanyixia.jpg" width="100%"></div>'+
                        '<div class="column2-bottom-activitydetail-button right"><img src="image/oneday-activity-yibanban.jpg" width="100%"></div>'+
                        '</div>').attr('aid',aid);
                    $('.column2-bottom-activitydetail-author-touxiang img').css('height',$('.column2-bottom-activitydetail-author-touxiang img').css('width')).css('border-radius','50%');
                    $('.column2-bottom-activitydetail-content').html(gaihuilai(data.content));
                    if(userinfo.logoPic!=''){
                        $('.column2-bottom-activitydetail-author-touxiang img').attr('src','../server/Public'+userinfo.logoPic);
                    }else{
                        $('.column2-bottom-activitydetail-author-touxiang img').attr('src','image/oneday-weishangchuan.png');
                    }
                    if(inArray(id,data.participant)){
                        $('.column2-bottom-activitydetail-top-joinin img').attr('src','image/oneday-join-havein.png')
                    }
                    for(var i=0;(i<data.participant.length)&&(i<7);i++){
                        $.post(geturl(apiBaseurl,'Home','User','getUserInfo'),{uid:data.participant[i]},function(userinfo){
                            console.log(userinfo.logoPic);
                            if(userinfo.logoPic==''){
                                var src='image/oneday-weishangchuan.png';
                            }else{
                                var src='../server/Public'+userinfo.logoPic;
                            }
                            $('.column2-bottom-activitydetail-participant').append('<div class="left column2-bottom-activitydetail-participant-item">'+
                                '<img src="'+src+'" width="100%"/>'+
                                '</div>')
                            $('.column2-bottom-activitydetail-participant-item img').css('height',$('.column2-bottom-activitydetail-participant-item img').css('width')).css('border-radius','50%');
                        })
                    }

                })
            })
        })
    })
    function inArray(yuansu,array){
        for(var i=0;i<array.length;i++){
            if(array[i]==yuansu){
                return true;
            }
        }
        return false;
    }
    //参加点击
    $('body').on('click','.column2-bottom-activitydetail-top-joinin img',function(){
        var thisobj=$(this);
        if(thisobj.attr('src')=='image/oneday-join-havein.png'){
            alert('已经参加了哦')
        }else{
            $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(uid){
                $.post(geturl(apiBaseurl,'Home','Activity','addParticipant'),{uid:uid,aid:$('.column2-bottom').attr('aid')},function(data){
                    if(data=='true'){
                        alert('报名成功');
                        thisobj.attr('src','image/oneday-join-havein.png');
                    }
                })
            })
        }
    })
    function gaihuilai(str){
        str=str.replace(/&lt;/g,'<');
        str=str.replace(/&gt;/g,'>');
        str=str.replace(/&quot;/g,'');
        return str;
    }
    //搜索栏聚焦事件
    $('.column1-search input').focus(function(){
        $('.column1-search img').attr('src','image/oneday-delete.png');
    })
    //搜索栏失焦事件
    $('.column1-search input').blur(function(){
        $('.column1-search img').attr('src','image/oneday-fangda.png');
    })
    //获取全部活动并放进activityarray中
    function getActivityArray(page){
        $.post(geturl(apiBaseurl,'Home','Activity','queryAll'),{page:pagenow,class:2},function(data2){
            $.post(geturl(apiBaseurl,'Home','Activity','queryAll'),{page:pagenow,class:3},function(data3){
                $.post(geturl(apiBaseurl,'Home','Activity','queryAll'),{page:pagenow,class:4},function(data4){
                    activityarray.splice(0,activityarray.length);
                    if(data2!='error'){
                        $.each(data2,function(i,d){
                            activityarray.push(d);
                        })
                    }
                    if(data3!='error'){
                        $.each(data3,function(i,d){
                            activityarray.push(d);
                        })
                    }
                    if(data4!='error'){
                        $.each(data4,function(i,d){
                            activityarray.push(d);
                        })
                    }
                })
            })
        })
    }
    /*$('.column2-date-calender').click(function(){
        $(this).css('background','#2e3440').find('img').attr('src','image/oneday-canleder-on.png');
    })*/
    //点击翻转事件
    $('.reverge-tag').click(function(){
        $.each($('.reverge-tag'),function(i,d){
            $(d).css('background','url("image/'+$(d).attr('id')+'.jpg")');
        })
        $(this).css('background','url("image/'+($(this).attr('id').split('.'))[0]+'-on'+'.jpg'+'")');
        event.stopPropagation();
    })
    $('body').width($(window).width());
    $('#container').height($(window).width()*0.57);
    $('body').on('click',"input[name='starttime']",function(){
        $("input[name='starttime']").datetimepicker();
    })
    $('body').on('click',"input[name='endtime']",function(){
        $("input[name='endtime']").datetimepicker();
    })
    $('body').on('click','.column3-bottom-option-new-confirm',function(){
        if(validatenull($('input[name=checkoption]'))){
            var option=$('input[name=checkoption]').val();
            $('.column3-bottom-group').append(
                '<div class="option">'+
                '<label value="'+option+'"><input type="checkbox" name="check"/>'+option+'</label>'+
                '</div>'
            )
            $('.column3-bottom-option-input input').val('');
        }
    })
    //快速添加
    $('body').on('focus','.column2-bottom-addbutton',function(){
        if($(this).val()=='点此快速新建任务')
            $(this).val('');
        $(this).css('color','#666');
        var thisobj=$(this);
        var nexttime=new Date(timenow.getTime()+60*60*1000);
        $(this)[0].addEventListener('keydown',function(e){
            if(e.keyCode==13){
                $.post(geturl(apiBaseurl,'Home','Schedule','create'),{
                    title:thisobj.val(),
                    tag:null,
                    location:null,
                    startTime:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()+' '+timenow.getHours()+':'+checktime(timenow.getMinutes())+':'+'00',/*starttimeobj.month+'-'+starttimeobj.day+'-'+' '+starttimeobj.hour+':'+starttimeobj.minute+':'+'00';*/
                    endTime:nexttime.getFullYear()+'-'+(nexttime.getMonth()+1)+'-'+nexttime.getDate()+' '+nexttime.getHours()+':'+checktime(nexttime.getMinutes())+':'+'00',
                    content:null,
                    check:null,
                    participant:null
                },function(result){
                    jiazai();
                })
            }
        })
    });
    //评论按钮逻辑
    $('body').on('click','.activity-pinglun-container-item-button',function(){
        var thisobj=$(this);
        $(this).parent().find('.activity-pinglun-container-item-textarea').slideToggle();
        if(!thisobj.attr('state')){
            thisobj.attr('state','on').find('img').attr('src','image/oneday-pinglun-tubiao-on.png').end().find('span').css('color','#ed7a72');
        }else{
            thisobj.removeAttr('state').find('img').attr('src','image/oneday-pinglun-tubiao.png').end().find('span').css('color','#bdc0b9');
        }
    })
    $('body').on('blur','.column2-bottom-addbutton',function(){
        if($(this).val()=='')
            $(this).val('点此快速新建任务');
        $(this).css('color','#dddddd');
    });
    $('body').on('click','.item-cotainer-border',function(){
        $.post(geturl(apiBaseurl,'Home','Schedule','query'),{sid:$(this).parent().parent().attr('sid')},function(data){
            drawc3rilidetail(data);
        })
    })
    $('body').on('click','.column3-bottom-confirm',function(){
        var optionnum=$('.column3-bottom-group .option').length;
        var title=$("input[name='title']").val();
        var tag=[];
        tag.push($("input[name='tag']").val());
        var destination=$("input[name='destination']").val();
        var starttime=$("input[name='starttime']").val();
        var endtime=$("input[name='endtime']").val();
        var check=new Array();
        starttimeobj=timeformat(starttime);
        starttime1=starttimeobj.year+'-'+starttimeobj.month+'-'+starttimeobj.day+'-'+' '+starttimeobj.hour+':'+starttimeobj.minute+':'+'00';
        endtimeobj=timeformat(endtime);
        endtime1=endtimeobj.year+'-'+endtimeobj.month+'-'+endtimeobj.day+'-'+' '+endtimeobj.hour+':'+endtimeobj.minute+':'+'00';
        var description=$("textarea[name='description']").val();
        if($(this).html()=='确 认'){
            if(validatenull($("input[name='title']"))&&validatenull($("input[name='tag']"))&&validatenull($("input[name='destination']"))&&validatenull($("input[name='starttime']"))&&validatenull($("input[name='endtime']"))&&validatenull($("input[name='description']"))){
                if(optionnum){
                    var checkall=$('.column3-bottom-group .option');
                    $.each(checkall,function(i,d){
                        var checkitem={content:$(d).find('label').attr('value'),state:1};
                        check.push(checkitem);
                    });
                    var checkstr=JSON.stringify(check);
                    var tag=JSON.stringify(tag);
                    $.post(geturl(apiBaseurl,'Home','Schedule','create'),
                        {
                            title:title,
                            tag:tag,
                            location:destination,
                            startTime:starttime1,
                            endTime:endtime1,
                            content:description==null?'null':destination,
                            check:checkstr,
                            participant:null
                        },
                        function (result){
                            if(result=='true'){
                                jiazai();
                                drawc3rili();
                            }else{
                                alert('似乎出了一些问题');
                            }
                        });
                }else{
                    alert('您还没有输入检查项哦');
                }
            }
        }else if($(this).html()=='修改'){
            if(validatenull($("input[name='title']"))&&validatenull($("input[name='tag']"))&&validatenull($("input[name='destination']"))&&validatenull($("input[name='starttime']"))&&validatenull($("input[name='endtime']"))&&validatenull($("input[name='description']"))){
                if(optionnum){
                    var checkall=$('.column3-bottom-group .option');
                    $.each(checkall,function(i,d){
                        var checkitem={content:$(d).find('label').attr('value'),state:1};
                        check.push(checkitem);
                    });
                    var checkstr=JSON.stringify(check);
                    var tag=JSON.stringify(tag);
                    $.post(geturl(apiBaseurl,'Home','Schedule','edit'),
                        {
                            sid:$('.column3-bottom').attr('sid'),
                            title:title,
                            tag:tag,
                            location:destination,
                            startTime:starttime1,
                            endTime:endtime1,
                            content:description==null?'null':destination,
                            check:checkstr,
                            participant:null
                        },
                        function (result){
                            if(result=='true'){
                                jiazai();
                                alert('修改成功');
                                drawc3rili();
                            }else{
                                alert('似乎出了一些问题');
                            }
                        });
                }else{
                    alert('您还没有输入检查项哦');
                }
            }
        }

    })
//时间格式化
    function timeformat(str){
        var array=new Object();
        var temparray=new Array();
        temparray=str.split(':');
        array.minute=temparray[1];
        temparray=temparray[0].split(' ');
        array.hour=temparray[1];
        temparray=temparray[0].split('/');
        array.year=temparray[0];
        array.month=temparray[1];
        array.day=temparray[2];
        return array;
    }
    function checktime(str){
        if(str<10){
            return '0'+str;
        }else{
            return str;
        }
    }
//得到某一天的位置
    function getposition(str,firstday){
        var day=(str.split('-'))[2];
        var row=parseInt((day-1+firstday)/7);
        return row;
    }
//表单验证
    function validatenull(obj){
        $.validity.start();
        obj.require('请写一些字哦');
        var result= $.validity.end();
        return result.valid;
    }
//数据库时间转化为js时间
    function dbtimetojsdate(str){
        var timestr=str.replace(/-/g,"/");
        return new Date(timestr);
    }
//1秒刷新一次
    function refresh(){
        for(var i= 0;i<itemarray.length;i++){
            var now=new Date;
            var width=getpercentage(now,dbtimetojsdate(itemarray[i].startTime),dbtimetojsdate(itemarray[i].endTime));
            if(width=='100%'||itemarray[i].state!=0){
                $('.item-container').find("[sid='"+itemarray[i].sid+"']").css('display','none');
            }else{
                $('.item-container').find("[sid='"+itemarray[i].sid+"']").find('.item-cotainer-content').css('width',width);
            }

        }
        timeout=setTimeout(refresh,1000);
    }
//加载活动全部
    function jiazaiActivity(pagenow){
        $.post(geturl(apiBaseurl,'Home','Activity','queryAll'),{page:pagenow,class:2},function(data2){
            $.post(geturl(apiBaseurl,'Home','Activity','queryAll'),{page:pagenow,class:3},function(data3){
                $.post(geturl(apiBaseurl,'Home','Activity','queryAll'),{page:pagenow,class:4},function(data4){
                    initActivity(data2,data3,data4);
                    drawAcitivtyList()
                })
            })
        })
    }
//加载活动数据
    function initActivity(data2,data3,data4){
        activityarray.splice(0,activityarray.length);
        if(data2!='error'){
            $.each(data2.content,function(i,d){
                activityarray.push(d);
            })
        }
        if(data3!='error'){
            $.each(data3.content,function(i,d){
                activityarray.push(d);
            })
        }
        if(data4!='error'){
            $.each(data4.content,function(i,d){
                activityarray.push(d);
            })
        }


    }
//画出活动dom
    function drawAcitivtyList(){
        $('.column2-bottom').html('<div class="column2-bottom-activity-container"></div>');
        for(var i=0;i<activityarray.length;i++){
            $('.column2-bottom-activity-container').append('<div class="column2-bottom-activity-item left" uid="'+activityarray[i].uid+'" aid="'+activityarray[i].aid+'">'+
                    '<h1>'+activityarray[i].title+'</h1>'+
                    '<img src="../server/Public'+activityarray[i].logoPic+'"/>'+
                    '</div>'
            );
        }
    }
//得到百分比宽度
    function getpercentage(now,start,end){
        if(now.getTime()>end.getTime()){
            return '100%';
        }else if(now.getTime()<=start.getTime()){
            return '0';
        }else{
            return parseFloat((now.getTime()-start.getTime())/(end.getTime()-start.getTime()))*100+'%';
        }
    }
//注入数组
    function init(obj){
        itemarray.splice(0,itemarray.length);
        $.each(obj,function(i,d){
            itemarray.push(d);
        });
        itemarray.sort(function(a,b){
            return parseInt(dbtimetojsdate(a.startTime).getTime())-parseInt(dbtimetojsdate(b.startTime).getTime());
        })
    }
//初始化日历界面
    function jiazai(){
        $('.column2-bottom').attr('state','rishitu');
        $('.column2-bottom').html(
                '<input class="column2-bottom-addbutton" value="点此快速新建任务"/>'+
                '<div class="item-container">'+
                '</div>'
        );
        $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(uid){
            $.post(geturl(apiBaseurl,'Home','Schedule','day'),{uid:uid,date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
                init(data);
                for(var i=0;i<itemarray.length;i++){
                    var timestr= itemarray[i].startTime.replace(/-/g,"/");
                    var date=new Date(timestr);
                    $('.item-container').append(
                            '<div class="item" sid="'+ itemarray[i].sid+'">'+
                            '<div class="left item-container-time">'+
                            checktime(date.getHours())+':'+checktime(date.getMinutes())+
                            '</div>'+
                            '<div class="left item-container-circle">'+
                            '</div>'+
                            '<div class="item-container-con">'+
                            '<div class="left item-cotainer-border">'+
                            '<div class="item-cotainer-content">'+
                                itemarray[i].title+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'
                    );
                }
                refresh();
            })
        })
        $('.column2-bottom').css('background','url("image/oneday-dayview-bg.png")').css('background-size','100%');
    }
    function jiazaishuju() {
        $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(uid){
            $.post(geturl(apiBaseurl,'Home','Schedule','day'),{uid:uid,date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
                init(data);
                refresh();
            })
        })
    }
    function initMonth(obj,timenow){
        var array=new Array();
        $.each(obj,function(i,d){
            array.push(d);
        })
        array.sort(function(a,b){
            return parseInt(dbtimetojsdate(a.startTime).getTime())-parseInt(dbtimetojsdate(b.startTime).getTime());
        })
        var montharray=array;
        var firstday=new Date(timenow.getFullYear()+"/"+(timenow.getMonth()+1)+'/1');
        globelmonthobj={addDate:function(str){
            if(this[str]){
                this[str]++;
            }else{
                this[str]=1;
            }
        }};
        for(var i=0;i<montharray.length;i++){
            globelmonthobj.addDate((montharray[i].startTime.split(' '))[0]);
        }
        for(var i in globelmonthobj){
            if(typeof globelmonthobj[i]=='number'){
                var date=new Date(i.replace(/-/g,'/'));
                var row=getposition(i,firstday.getDay());
                $(".column2-bottom-calender-container:eq("+row+")").find('td:eq('+ date.getDay()+')').attr('date',i).css('cursor','pointer').html('<div class="td-day">'+date.getDate()+'</div><div class="td-num"><b>'+globelmonthobj[i]+'</b>个</div>').css('background','#f9d1ce');
            }
        }
    }
    //画日视图点击详情
    function drawc3rilidetail(data){
        if(data.state==1){
            var src="image/oneday-confirm-button-on.png";
        }else{
            var src="image/oneday-confirm-button.png";
        }
        $('.column3-bottom').attr('sid',data.sid).html('<div class="column3-bottom-taskdetail">'+
            '<div class="column3-bottom-taskdetail-header">'+
            '<img src="image/oneday-rightarrow.png" class="rightarrow"/>'+
            '<span class="column3-bottom-taskdetail-header-title">任务标题</span>'+
            '<span class="column3-bottom-taskdetail-header-ddl">DDL&nbsp12:45a.m</span>'+
            '<img src="'+src+'" class="confirm"/>'+
            '</div>'+
            '<div class="column3-bottom-taskdetail-content">'+
            '<h6>地点&nbsp&nbsp&nbsp'+data.location+'</h6>'+
            '<h6>描述&nbsp&nbsp&nbsp'+data.content+'</h6>'+
            '</div>'+
            '<div class="column3-bottom-taskdetail-bottom">'+
            '<div id="editor" style="width: 220px;height: 150px;float: right;margin-right: 20px"></div>'+
            '<div class="column3-bottom-taskdetail-bottom-buttons">'+
            '<div class="column3-bottom-taskdetail-bottom-button" id="column3-bottom-taskdetail-bottom-buttons-confirm">'+
            '确认'+
            '</div>'+
            '<div class="column3-bottom-taskdetail-bottom-button" id="column3-bottom-taskdetail-bottom-buttons-cancel">'+
            '取消'+
            '</div>'+
            '</div>'+
            '<div class="column3-bottom-taskdetail-left">'+
            '<img src="image/oneday-taskdetail-page.png"/>'+
            '<img src="image/oneday-taskdetail-img.png"/>'+
            '<img src="image/oneday-taskdetail-other.png"/>'+
            '</div>'+
            '</div>'+
            '</div>').attr('state','detail');
        if(!UEDITORFLAG){
            ue = UE.getEditor('editor',{
                toolbars:[['bold','italic','underline','strikethrough','insertorderedlist','insertunorderedlist']],
                autoHeightEnabled:true,
                autoFloatEnabled: true,
                enableAutoSave:true
            });
            UEDITORFLAG=true;
        }else{
            ue.destroy();
            ue = UE.getEditor('editor',{
                toolbars:[['bold','italic','underline','strikethrough','insertorderedlist','insertunorderedlist']],
                autoHeightEnabled:true,
                autoFloatEnabled: true,
                enableAutoSave:true
            });
            $('table.edui-default').css('display','none');/**/
        }
    }
    //画日视图右侧添加按钮
    function drawc3rili(){
        $('.column3-bottom').html('<div class="column3-bottom-title">'+
            '<div class="left"><img src="image/oneday-rightarrow.png"></div>'+
        '正在创建新任务'+
        '</div>'+
        '<form id="newtask">'+
            '<div class="column3-bottom-input">'+
                '<label>标题</label><input type="text" name="title" placeholder="在此输入标题"/>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
                '<label>标签</label><input type="text"  name="tag"placeholder="添加标签"/>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
                '<label>地点</label><input type="text"  name="destination" placeholder="在此输入地点"/>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
                '<label>始于</label><input type="text" name="starttime"/>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
                '<label>止于</label><input type="text" name="endtime"/>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
                '<label>描述</label><textarea name="description"/></textarea>'+
        '</div>'+
        '<div class="column3-bottom-option">'+
            '<div class="column3-bottom-option-label left">检查项</div>'+
            '<div class="column3-bottom-group left">'+
            '</div>'+
                '<div class="column3-bottom-option-new">'+
                    '<div class="column3-bottom-option-input">'+
                        '<input type="text" placeholder="输入检查项内容" name="checkoption"/>'+
                    '</div>'+
                    '<div class="clear">'+
                        '<div class="column3-bottom-option-new-confirm left">添 加</div>'+
                        '<div class="column3-bottom-option-new-cancel left">取 消</div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '</form>'+
        '<div class="column3-bottom-person overflow">'+
            '<div class="column3-bottom-person-label left">参与者</div>'+
            '<div class="column3-bottom-person-img left">'+
                '<img src="image/oneday-addperson.png" width="50px" height="50px"/>'+
            '</div>'+
            '<div id="addperson">'+
            '</div>'+
        '</div>'+
        '<div class="column3-bottom-cancel left">取 消</div>'+
        '<div class="column3-bottom-confirm left">确 认</div>');
    }
    //画月视图框架
    function drawcalcu(){
        $('.column2-bottom').attr('state','yueshitu');
        $('.column2-bottom').html('<table class="column2-bottom-calender" rules=none cellspacing=0 align=center>'+
                '<tr class="column2-bottom-calender-title">'+
                '<th>周 日</th>'+
                '<th>周 一</th>'+
                '<th>周 二</th>'+
                '<th>周 三</th>'+
                '<th>周 四</th>'+
                '<th>周 五</th>'+
                '<th>周 六</th>'+
                '</tr>'+
                '<tr class="column2-bottom-calender-container">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '</tr>'+
                '<tr class="column2-bottom-calender-container">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '</tr>'+
                '<tr class="column2-bottom-calender-container">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '</tr>'+
                '<tr class="column2-bottom-calender-container">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '</tr>'+
                '<tr class="column2-bottom-calender-container">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '</tr>'+
                '<tr class="column2-bottom-calender-container">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '</tr>'+
                '</table>'
        );
        $('.column2-bottom-calender-container').find('td:first').css('background','#f9f9f9');
        $('.column2-bottom-calender-container').find('td:last').css('background','#f9f9f9');
    }
})