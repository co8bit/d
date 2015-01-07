/**
 * Created by Administrator on 2015/1/7.
 */
$(document).ready(function(){
    var height=$(window).height();
    var page=1;
    var isanimate=false;
    $('.page').css('width',$(window).width());
    $('.page').css('height',$(window).height());
    $(window).resize(function(){
        $('.page').css('width',$(window).width());
        $('.page').css('height',$(window).height());
    })
    document.body.onmousewheel=function(event){
        event=event||window.event;
        if(event.wheelDelta<0){
            if(!isanimate){
                if(page!=5){
                    isanimate=true;
                    page++;
                    $('#container').animate({top:'-='+height},800,function(){
                        isanimate=false;
                    });
                }
            }
        }else{
            if(!isanimate){
                if(page!=1){
                    isanimate=true;
                    page--;
                    $('#container').animate({top:'+='+height},800,function(){
                        isanimate=false;
                    });
                }
            }
        }
    }
    $('.page1-arrow img').mouseenter(function(){
        $(this).attr('src','image/oneday-page-arrow-on.png');
    })
    $('.page1-arrow img').mouseout(function(){
        $(this).attr('src','image/oneday-page-arrow.png');
    })
    $('.page1-arrow img').click(function(){
        isanimate=true;
        page++;
        $('#container').animate({top:'-='+height},800,function(){
            isanimate=false;
        });
    })
})