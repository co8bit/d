/**
 * Created by Administrator on 2014/12/8.
 */

$(document).ready(function(){
    var timeout;
    var timenow=new Date();
    var monthtimenow=new Date;
    var itemarray=new Array();
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
    //月视图单元格点击事件
    $('body').on('click','.column2-bottom-calender-container td',function(){
        timenow=dbtimetojsdate($(this).attr('date'));
        jiazai();
    })
    //周视图单元格点击事件
    $('body').on('click','.column2-bottom-week-container .column2-bottom-week-container-item',function(){
        timenow=dbtimetojsdate(($(this).attr('date')).replace(/-/g,'/'));
        jiazai();
    })
    //颜色替换
    function fanzhuan(num,length){
        if((parseInt(num/length)%2)==0){
            return num%length-1;
        }else{
            return (length-num%length)-1;
        }
    }
    jiazai();
    $('#oneday-calcu').click(function(){
        var timenow=new Date();
        drawcalcu();
        $.get('../server/index.php/Schedule/month',{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
            initMonth(data,timenow);
        });
    })
    $('#oneday-calender').click(function(){
        jiazaiweek();
    })
    function jiazaiweek(){
        drawweekdom();
        $.post('../server/index.php/Schedule/week',{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
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
            $.get('../server/index.php/Schedule/month',{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
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
            $.get('../server/index.php/Schedule/month',{date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
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
            case '发现活动':$('.column2-bottom').html('<div class="column2-bottom-activity-container">'+
                '<div class="column2-bottom-activity-item left">'+
                '<h1>练练——别在冬天放弃自己</h1>'+
                '<img src="image/OneDay-activity-itemimg.jpg"/>'+
                '</div>'+
                '<div class="column2-bottom-activity-item left">'+
                '<h1>练练——别在冬天放弃自己</h1>'+
                '<img src="image/OneDay-activity-itemimg.jpg"/>'+
                '</div>'+
                '<div class="column2-bottom-activity-item left">'+
                '<h1>练练——别在冬天放弃自己</h1>'+
                '<img src="image/OneDay-activity-itemimg.jpg"/>'+
                '</div>'+
                '<div class="column2-bottom-activity-item left">'+
                '<h1>练练——别在冬天放弃自己</h1>'+
                '<img src="image/OneDay-activity-itemimg.jpg"/>'+
                '</div>'+
                '<div class="column2-bottom-activity-item left">'+
                '<h1>练练——别在冬天放弃自己</h1>'+
                '<img src="image/OneDay-activity-itemimg.jpg"/>'+
                '</div>'+
                '<div class="column2-bottom-activity-item left">'+
                '<h1>练练——别在冬天放弃自己</h1>'+
                '<img src="image/OneDay-activity-itemimg.jpg"/>'+
                '</div>'+
                '</div>');
                $('.column3-bottom').html('<div class="column3-bottom-activity-title"><img src="image/oneday-rightarrow.png"/>热门活动</div>'+
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
                break;
        }
        $('.column1-tag').css('background','#fff').css('color','#999999').css('font-weight','normal').find('img').attr('src','image/oneday-rightarrow.png');
        $(this).css('background','#f4a8a3').css('color','#fff').css('font-weight','bold').find('img').attr('src','image/oneday-rightarrow-on.png');
    })





    var optionnum=0;
    $('.column1-search input').focus(function(){
        $('.column1-search img').attr('src','image/oneday-delete.png');
    })
    $('.column1-search input').blur(function(){
        $('.column1-search img').attr('src','image/oneday-fangda.png');
    })


    $('.column2-date-calender').click(function(){
        $(this).css('background','#2e3440').find('img').attr('src','image/oneday-canleder-on.png');
    })
    $('.reverge-tag').click(function(){
        $.each($('.reverge-tag'),function(i,d){
            $(d).css('background','url("image/'+$(d).attr('id')+'.jpg")');
        })
        $(this).css('background','url("image/'+($(this).attr('id').split('.'))[0]+'-on'+'.jpg'+'")');
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
            optionnum++;
            $('.column3-bottom-option-input input').val('');
        }
    })
    $('body').on('click','.column2-bottom-addbutton',function(){
        drawc3rili();
    });
    $('body').on('click','.item-cotainer-content',function(){
        $.post('../server/index.php/Schedule/query',{sid:$(this).parent().parent().parent().attr('sid')},function(data){
            drawc3rilidetail(data);
        })
    })
    $('body').on('click','.column3-bottom-confirm',function(){
        var title=$("input[name='title']").val();
        var tag=$("input[name='tag']").val();
        var destination=$("input[name='destination']").val();
        var starttime=$("input[name='starttime']").val();
        var endtime=$("input[name='endtime']").val();
        var check=new Array();
        starttimeobj=timeformat(starttime);
        starttime1=starttimeobj.year+'-'+starttimeobj.month+'-'+starttimeobj.day+'-'+' '+starttimeobj.hour+':'+starttimeobj.minute+':'+'00';
        endtimeobj=timeformat(endtime);
        endtime1=endtimeobj.year+'-'+endtimeobj.month+'-'+endtimeobj.day+'-'+' '+endtimeobj.hour+':'+endtimeobj.minute+':'+'00';
        var description=$("textarea[name='description']").val();
        if(validatenull($("input[name='title']"))&&validatenull($("input[name='tag']"))&&validatenull($("input[name='destination']"))&&validatenull($("input[name='starttime']"))&&validatenull($("input[name='endtime']"))&&validatenull($("input[name='description']"))){
            if(optionnum){
                var checkall=$('.column3-bottom-group .option');
                $.each(checkall,function(i,d){
                    var checkitem={content:$(d).find('label').attr('value'),state:1};
                    check.push(checkitem);
                });
                var checkstr=JSON.stringify(check);
                $.post("../server/index.php/Schedule/create",
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
                    }else{
                        alert('似乎出了一些问题');
                    }
                });
            }else{
                alert('您还没有输入检查项哦');
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
            $('.item-container').find("[sid='"+itemarray[i].sid+"']").find('.item-cotainer-content').css('width',getpercentage(now,dbtimetojsdate(itemarray[i].startTime),dbtimetojsdate(itemarray[i].endTime)));
        }
        timeout=setTimeout(refresh,1000);
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
        $('.column2-bottom').html('');
        $('.column2-bottom').html(
                '<div class="column2-bottom-addbutton">'+
                '点此快速新建任务'+
                '</div>'+
                '<div class="item-container">'+
                '</div>'
        );
        $.post('../server/index.php/User/getUid',{},function(uid){
            $.post('../server/index.php/Schedule/day',{uid:uid,date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
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
    }
    function jiazaishuju() {
        $.post('../server/index.php/User/getUid',{},function(uid){
            $.get('../server/index.php/Schedule/day',{uid:uid,date:timenow.getFullYear()+'-'+(timenow.getMonth()+1)+'-'+timenow.getDate()},function(data){
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
        $('.column3-bottom').html('<div class="column3-bottom-taskdetail">'+
            '<div class="column3-bottom-taskdetail-header">'+
            '<img src="image/oneday-rightarrow.png" class="rightarrow"/>'+
            '<span class="column3-bottom-taskdetail-header-title">任务标题</span>'+
            '<span class="column3-bottom-taskdetail-header-ddl">DDL&nbsp12:45a.m</span>'+
            '<img src="image/oneday-confirm-button.png" class="confirm"/>'+
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
            '</div>');
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
            '<label>标题<input type="text" name="title" placeholder="在此输入标题"/></label>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
            '<label>标签<input type="text"  name="tag"placeholder="添加标签"/></label>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
            '<label>地点<input type="text"  name="destination" placeholder="在此输入地点"/></label>'+
            '</div>'+
            '<div class="column3-bottom-inputtime">'+
            '<div class="label left">时间</div>'+
            '<div class="left">'+
            '<div class="overflow">'+
            '<label class="left">始于<input type="text" name="starttime"/></label>'+
            '</div>'+
            '<div class="overflow">'+
            '<label class="left">止于<input type="text" name="endtime"/></label>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '<div class="column3-bottom-input">'+
            '<label>描述<textarea name="description"/></textarea></label>'+
            '</div>'+
            '<div class="column3-bottom-option">'+
            '<div class="column3-bottom-option-label left">检查项</div>'+
            '<div class="column3-bottom-group left">'+
            '</div>'+
            '<div class="column3-bottom-option-new">'+
            '<div class="column3-bottom-option-input">'+
            '<input type="text" placeholder="输入检查项内容" name="checkoption"/>'+
            '</div>'+
            '<div class="column3-bottom-option-new-confirm left">添 加</div>'+
            '<div class="column3-bottom-option-new-cancel left">取 消</div>'+
            '</div>'+
            '</div>'+
            '</form>'+
            '<div class="column3-bottom-person overflow">'+
            '<div class="column3-bottom-person-label left">参与者</div>'+
            '<div class="column3-bottom-person-img left">'+
            '<img src="image/oneday-addperson.png" width="50px" height="50px"/>'+
            '<img src="image/oneday-addperson.png" width="50px" height="50px"/>'+
            '<img src="image/oneday-addperson.png" width="50px" height="50px"/>'+
            '</div>'+
            '<div id="addperson">'+
            '</div>'+
            '</div>'+
            '<div class="column3-bottom-cancel left">取 消</div>'+
            '<div class="column3-bottom-confirm left">确 认</div>');
        $('.column2-bottom').css('background','url("image/oneday-dayview-bg.png")').css('background-size','100%');
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