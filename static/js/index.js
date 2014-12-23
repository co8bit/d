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
    $('.column2-date-time').html('周'+timenow.getDay()+','+(timenow.getMonth()+1)+'月'+timenow.getDate()+'日');
    var optionnum=0;
    $('.column1-search input').focus(function(){
        $('.column1-search img').attr('src','image/oneday-delete.png');
    })
    $('.column1-search input').blur(function(){
        $('.column1-search img').attr('src','image/oneday-fangda.png');
    })
    $('.column1-tag').click(function(){
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
    $('.column3-bottom-option-new-confirm').click(function(){
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
    $('.column3-bottom-confirm').click(function(){
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