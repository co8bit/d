/**
 * Created by Administrator on 2014/12/8.
 */
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
//表单验证
function validatenull(obj){
    $.validity.start();
    obj.require('请写一些字哦');
    var result= $.validity.end();
    return result.valid;
}
//1秒刷新一次
function refresh(){
    var now=new Date();
    $.each($('.item-container'),function(i,d){
        $(d).css()
    })
}
$(document).ready(function(){
    var timenow=new Date();
    var UEDITORFLAG=false;
    var ue;
    $('.column2-date-time').html('周'+timenow.getDay()+','+(timenow.getMonth()+1)+'月'+timenow.getDate()+'日');
    var optionnum=0;
    $('.column1-search input').focus(function(){
        $('.column1-search img').attr('src','image/oneday-delete.png');
    })
    $('.column1-search input').blur(function(){
        $('.column1-search img').attr('src','image/oneday-fangda.png');
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
            case '日历':$('.column2-bottom').html('<div class="column2-bottom-addbutton">'+
                '点此快速新建任务'+
                '</div>'+
            '<div class="item-container">'+
                '<div class="item">'+
                    '<div class="left item-container-time">'+
                    '07:15'+
                    '</div>'+
                    '<div class="left item-container-circle">'+
                    '</div>'+
                    '<div class="item-container-con">'+
                        '<div class="left item-cotainer-border">'+
                            '<div class="item-cotainer-content">'+
                            '起床'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>');
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
                '<div class="option">'+
                '<label><input type="checkbox" name="check"/>苹果</label>'+
            '</div>'+
            '<div class="option">'+
                '<label><input type="checkbox" name="check"/>苹果</label>'+
                '</div>'+
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
                break;
        }
        $('.column1-tag').css('background','#fff').css('color','#999999').css('font-weight','normal').find('img').attr('src','image/oneday-rightarrow.png');
        $(this).css('background','#f4a8a3').css('color','#fff').css('font-weight','bold').find('img').attr('src','image/oneday-rightarrow-on.png');
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
    $("input[name='starttime']").datetimepicker();
    $("input[name='endtime']").datetimepicker();
    $('.column2-bottom-addbutton').click(function(){
    });
    $('body').on('click','.column3-bottom-option-new-confirm',function(){
        if(validatenull($('input[name=checkoption]'))){
            var option=$('input[name=checkoption]').val();
            $('.column3-bottom-group').append(
                '<div class="option">'+
                '<label><input type="checkbox" name="check"/>'+option+'</label>'+
                '</div>'
            )
        }
        optionnum++;
    })
    $('body').on('click','.column2-bottom-addbutton',function(){
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
            '<div class="option">'+
                '<label><input type="checkbox" name="check"/>苹果</label>'+
            '</div>'+
            '<div class="option">'+
            '<label><input type="checkbox" name="check"/>苹果</label>'+
            '</div>'+
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
    });
    $('body').on('click','.item-cotainer-content',function(){
        $('.column3-bottom').html('<div class="column3-bottom-taskdetail">'+
            '<div class="column3-bottom-taskdetail-header">'+
            '<img src="image/oneday-rightarrow.png" class="rightarrow"/>'+
            '<span class="column3-bottom-taskdetail-header-title">任务标题</span>'+
            '<span class="column3-bottom-taskdetail-header-ddl">DDL&nbsp12:45a.m</span>'+
            '<img src="image/oneday-confirm-button.png" class="confirm"/>'+
            '</div>'+
            '<div class="column3-bottom-taskdetail-content">'+
            '<h6>地点&nbsp&nbsp&nbsp哈哈哈哈</h6>'+
            '<h6>描述&nbsp&nbsp&nbsp哈哈哈哈哈哈哈哈哈哈哈</h6>'+
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
    })
    $('body').on('click','.column3-bottom-confirm',function(){
        var title=$("input[name='title']").val();
        var tag=$("input[name='tag']").val();
        var destination=$("input[name='destination']").val();
        var starttime=$("input[name='starttime']").val();
        var endtime=$("input[name='endtime']").val();
        starttimeobj=timeformat(starttime);
        endtimeobj=timeformat(endtime);
        var description=$("textarea[name='description']").val();
        if(validatenull($("input[name='title']"))&&validatenull($("input[name='tag']"))&&validatenull($("input[name='destination']"))&&validatenull($("input[name='starttime']"))&&validatenull($("input[name='endtime']"))&&validatenull($("input[name='description']"))){
            if(optionnum){
                if(description){
                    $('.item-container').append(
                            '<div class="item">'+
                            '<div class="left item-container-time">'+
                            starttimeobj['hour']+':'+starttimeobj['minute']+
                            '</div>'+
                            '<div class="left item-container-circle">'+
                            '</div>'+
                            '<div class="item-container-con">'+
                            '<div class="left item-cotainer-border">'+
                            '<div class="item-cotainer-content">'+
                            title+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '</div>'
                    )
                }else{
                    alert('描述一下您的任务');
                }
            }else{
                alert('您还没有输入检查项哦');
            }
        }
    })
})