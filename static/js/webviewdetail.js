/**
 * Created by ss on 15/5/26.
 */
$(document).ready(function(){
    var windowWidth = $(window).width();
    $('html').css('font-size',windowWidth/720*12+'px');
    var apiBaseurl='../../server/index.php?';
    var location = window.location.href;
    var id = (location.split('='))[1];
    var tmpl = $('script#itemtmpl').html();
    $.post(geturl(apiBaseurl,'Home','Activity','queryOne'),{aid:id},function(activity){
        $.post(geturl(apiBaseurl,'Home','User','getUserInfo'),{uid:activity.uid},function(userInfo){
            var src;
            console.log(activity);
            if(userInfo.logoPic){
                src='../../server/Public'+userInfo.logoPic;
            }else{
                src='../image/oneday-weishangchuan.png';
            }
            $('.personal-headshot').attr('src',src).css('width','1.8rem');
            $('.title').html(activity.title);
            $('.personal-name').html(userInfo.name);
            $('.time').html(getTime(hackDate(activity.startTime)));
            $('.des').html('活动地点：'+activity.location);
            $('.content').html(gaihuilai(activity.content));
            var comment=JSON.parse(activity.comment);
            var html='';
            for(var i=0;i<comment.length;i++){
                $.ajax({
                    async:false,
                    type:'POST',
                    url:geturl(apiBaseurl,'Home','User','getUserInfo'),
                    data:{uid:comment[i].uid},
                    success:function(userinfo){
                        var tmplobj =  {};
                        if(userinfo.realName==''){
                            tmplobj.name='这人没名字，么么哒';
                        }else{
                            tmplobj.name=userinfo.realName;
                        }
                        if(userinfo.logoPic){
                            tmplobj.image = userinfo.logoPic;
                        }else{
                            tmplobj.image = '../image/oneday-weishangchuan.png';
                        }
                        tmplobj.content = comment[i].content;
                        html+=formatTemplate(tmplobj,tmpl);
                    }
                })
            }
            $('.comment').html(html);
        })
    })
    $('.return-confirm').click(function(){
        $.post(geturl(apiBaseurl,'Home','User','getUid'),{},function(uid){
            $.post(geturl(apiBaseurl,'Home','User','getUserInfo'),{uid:uid},function(userInfo){
                var comment = $('.return textarea').val();
                $.post(geturl(apiBaseurl,'Home','Activity','addComment'),{'aid':id,'content':comment,uid:uid},function(data){
                    if(data=='true'){
                        var tmplobj =  {};
                        if(userInfo.realName==''){
                            tmplobj.name='这人没名字，么么哒';
                        }else{
                            tmplobj.name=userInfo.realName;
                        }
                        if(userInfo.logoPic){
                            tmplobj.image = userInfo.logoPic;
                        }else{
                            tmplobj.image = '../image/oneday-weishangchuan.png';
                        }
                        tmplobj.content = comment;
                        $('.comment').append(formatTemplate(tmplobj,tmpl));
                        $('.return textarea').val('');
                    }
                })
            })
        })
    })
    function getTime(date){
        return '活动时间：'+(date.getMonth()+1)+'月'+date.getDate()+'日'+' （周'+getWeekday(date.getDay())+'），'+date.getHours()+':'+date.getMinutes();
    }
    function getWeekday(num){
        var array=['日','一','二','三','四','五','六'];
        return array[num];
    }
    function hackDate(str){
        return new Date(str.replace('-','/'));
    }
    function gaihuilai(str){
        str=str.replace(/&lt;/g,'<');
        str=str.replace(/&gt;/g,'>');
        str=str.replace(/&quot;/g,'');
        return str;
    }
    function geturl(api,m,c,a){
        return api+'m='+m+'&c='+c+'&a='+a;
    }
    function formatTemplate(dta, tmpl) {
        var format = {
            name: function(x) {
                return x
            }
        };
        return tmpl.replace(/{(\w+)}/g, function(m1, m2) {
            if (!m2)
                return "";
            return (format && format[m2]) ? format[m2](dta[m2]) : dta[m2];
        });
    }
})