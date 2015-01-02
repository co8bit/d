/**
 * Created by Administrator on 2014/12/17.
 */
//邮箱验证
function validateEmail(){
    $.validity.start();
    $('.container-content-email input').require('邮箱地址不能为空').match('email','请填写正确的邮箱地址');
    var result= $.validity.end();
    return result.valid;
}
function validatepassword(){
    $.validity.start();
    $('.container-content-password input').require('密码不能为空').minLength(6,"密码不能少于6位哦");
    var result= $.validity.end();
    return result.valid;
}
$(document).ready(function(){
    /*$('#black').css('width',$(window).width()).css('height',$(window).height());*/
    var apiBaseurl='../server/index.php?';
    function geturl(api,m,c,a){
        return api+'m='+m+'&c='+c+'&a='+a;
    }

    var width=$(window).width();
    var height=$(document).height();
    $('.container-content-email input').focus(function(){
        if($(this).val()=='Email'){
            $(this).val('');
        }
    });
    $('.container-content-login').click(function(){
        if(validateEmail()&&validatepassword()){
            var username=$('.container-content-email input').val();
            var password=hex_md5($('.container-content-password input').val());
            $.post(geturl(apiBaseurl,'Home','User','login'),
                {userName:username,userPassword:password},
                function (result){
                    console.log(geturl(apiBaseurl,'Home','User','login'));
                    if(result=='true'){
                        console.log(geturl(apiBaseurl,'Home','User','login'));
                        $('.container').animate({opacity:0},500,function(){
                            window.location.href="index.html";
                        });
                    }else{
                        alert('用户名或密码错误');
                    }
                });
        }
    })
    $('.container-content-register').click(function(){
        if(validateEmail()&&validatepassword()){
            var username=$('.container-content-email input').val();
            var password= hex_md5($('.container-content-password input').val());
            $.post(geturl(apiBaseurl,'Home','User','sign'),
                {userName:username,userPassword:password},
                function (result){
                    if(result=='true'){
                        $.post(geturl(apiBaseurl,'Home','User','login'),
                            {userName:username,userPassword:password},
                            function (result){
                                if (result == 'true') {
                                    $('.container').animate({opacity: 0}, 500, function () {
                                        window.location.href = "index.html";
                                    });
                                }
                            }
                        )
                    }
                }
            );
        }
    })
    /*$.getJSON(apiBaseUrl,function(data){
        console.log(data);
        alert(123);
    })*/
    $('.container-content-email input').blur(function(){
        if($(this).val()==''){
            $(this).val('Email');
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