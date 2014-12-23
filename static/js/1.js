(function() {
    //r:clienx;v:clientY;A:Date.now();
    (function(){
        function a(){
            $("body").mousemove(function(c){
                r=c.clientX;
                v=c.clientY;
                A=Date.now();w||p()});
                $(window).on("blur mouseout",function(){
                    v=r=null
                }).on("resize",function(){
                    c&&c.parentNode&&c.parentNode.removeChild(c);
                    e()
                });
            e()
        }
        function e(){
            var d=null==g?!0:!1;
            c=document.createElement("canvas");
            c.width=$(document).width();
            c.height=$(document).height();
            $("body").append(c);
            g=document.createElement("canvas");
            g.width=$(document).width();
            g.height=$(document).height();
            c.getContext&&c.getContext("2d")&&(q=c.getContext("2d"),f=g.getContext("2d"),f.lineCap="round",f.shadowColor="#000000",f.shadowBlur=30,h=new Image,h.src=$("body").css("background-image").replace(/url\((.*)\)/,"$1").replace(/\.jpg/,"_color.jpg"),h.onload=function(){d&&p()})}function p(){var a=Date.now();w=a>A+E?!1:!0;r&&w&&d.splice(0,0,{time:a,x:r,y:v});for(var b=0;b<d.length;)a-d[b].time>k?d.splice(b,d.length):b++;0<d.length&&window.n(p);f.clearRect(0,0,g.width,g.height);for(b=1;b<d.length;b++){var e=Math.sqrt(Math.pow(d[b].x-d[b-1].x,2)+Math.pow(d[b].y-
d[b-1].y,2));f.strokeStyle="rgba(0,0,0,"+Math.max(1-(a-d[b].time)/k,0)+")";f.lineWidth=s+Math.max(1-e/F,0)*(G-s);f.beginPath();f.moveTo(d[b-1].x,d[b-1].y);f.lineTo(d[b].x,d[b].y);f.stroke()}a=c.width;b=c.width/h.naturalWidth*h.naturalHeight;b<c.height&&(b=c.height,a=c.height/h.naturalHeight*h.naturalWidth);q.drawImage(h,0,0,a,b);q.globalCompositeOperation="destination-in";q.drawImage(g,0,0);q.globalCompositeOperation="source-over"}var k=1E3,s=25,G=100,F=50,E=k/2,c,g,q,f,h,r=null,v=null,d=[],A=0,w=
!0;"createTouch"in document||$(a);window.n=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||function(a){window.setTimeout(a,1E3/60)}}()})();function D(a){this.m=(this.j="PUBLIC"!==a)?a:"*"}D.prototype.init=function(a){window.addEventListener("message",function(e){e=e.data;e.isCanvaApi&&(a[e.type]||$.noop)(e)},!1);this.sendMessage({type:"Init",authenticated:this.j})};D.prototype.sendMessage=function(a){a.isCanvaApi=!0;window.parent.postMessage(a,this.m)};
window.setupCanvaEmbedded=function(a){function e(){$(document.body).removeClass("dialogView")}function p(){var a=new m.i({B:l.document.l().width,A:l.document.l().height,H:l.document.h().length}),e=new m.i.s({model:a.D()});e.F=function(){k.sendMessage({type:"Published",designId:l.o.id()})};a.h().C=e;a.h().k=new m.i.p({model:{}});a.J(a.h().k);a.show($("body"));a.v({G:ExportService.t.u},null)}var k=new D(a.embedDomain);$(".homeLink").click(function(a){a.preventDefault();k.sendMessage({type:"Home"})});
if(l&&m&&m.q)var s=$("#documentMenuExport button");k.init({setDoneButtonText:function(a){s.text(a.text)},blur:function(){$(document.body).addClass("dialogView")},focus:e,publish:function(){try{l.o.I(function(a){a&&p()})}catch(a){e()}}})};})();
