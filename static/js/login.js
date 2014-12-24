/**
 * Created by Administrator on 2014/12/17.
 */
$(document).ready(function(){
    /*$('#black').css('width',$(window).width()).css('height',$(window).height());*/
    var apiBaseUrl='../server/index.php/schedule/query/sid/1';
    $.getJSON(apiBaseUrl,function(data){
        alert(data);
    })
    var width=$(window).width();
    var height=$(document).height();
    $('.container-content-email input').focus(function(){
        if($(this).val()=='Email'){
            $(this).val('');
        }
    })
    $('.container-content-email input').blur(function(){
        if($(this).val()==''){
            $(this).val('Email');
        }
    })
    $('.container-content-password input').focus(function(){
        if($(this).val()=='Password'){
            $(this).val('');
        }
    })
    $('.container-content-password input').blur(function(){
        if($(this).val()==''){
            $(this).val('Password');
        }
    })
    $('.container-register b').click(function(){
        $('.container-register').css('display','none');
        $('.container-login').css('display','block');
    })
    $('.container-login img').click(function(){
        if($(this).attr('src')=='image/oneday-register-frame.jpg'){
            $(this).attr('src','image/oneday-register-frame-on.jpg');
        }else{
            $(this).attr('src','image/oneday-register-frame.jpg')
        }
    });
    $('.container-content-login').click(function(){
        window.location.href='index.html';
    })
    $('.container-top-login').click(function(){
        $('.container-register').css('display','none');
        $('.container-login').css('display','block');
    })
    $('.container-top-register').click(function(){
        $('.container-login').css('display','none');
        $('.container-register').css('display','block');
    })
    $('#black').attr('width',parseInt(width)).attr('height',parseInt(height));
})
function objlog(obj){
    for(var i in obj){
        console.log(i+':'+obj[i]);
    }
}