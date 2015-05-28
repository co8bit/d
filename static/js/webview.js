function change(number){

	//var a = document.getElementById('jinri');
	//var b = document.getElementById('jingpin');
	//var c = document.getElementById('paihang');
	//var d = document.getElementById('fenlei');
	//if(number == 1){
	//	a.style.display = "block";
	//	b.style.display = "none";
	//	c.style.display = "none";
	//	d.style.display = "none";
	//}
	//if(number == 2){
	//	a.style.display = "none";
	//	b.style.display = "block";
	//	c.style.display = "none";
	//	d.style.display = "none";
	//}
	//if(number == 3){
	//	a.style.display = "none";
	//	b.style.display = "none";
	//	c.style.display = "block";
	//	d.style.display = "none";
	//}
	//if(number == 4){
	//	a.style.display = "none";
	//	b.style.display = "none";
	//	c.style.display = "none";
	//	d.style.display = "block";
	//}

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

function drawDoms(array,tmpl){
	var html = '';
	for(var i = 0; i<array.length;i++){
		var item = array[i];
		console.log(item);
		var obj = {};
		obj.title = item.title;
		obj.startTime = (item.startTime.split(' '))[0];
		obj.logoPic = item.logoPic;
		obj.id = item.aid;
		html += formatTemplate(obj,tmpl);
	}
	return html;
}
$(document).ready(function(){
	var apiBaseurl='../../server/index.php?';
	var pagenow = 0;
	var tmpl = $('script#activityDom').html();
	function geturl(api,m,c,a){
		return api+'m='+m+'&c='+c+'&a='+a;
	}
	$.post(geturl(apiBaseurl,'Home','Activity','queryAll'),{},function(data){
		var html = drawDoms(data.content,tmpl);
		$('#jinri').append(html);
	})
})