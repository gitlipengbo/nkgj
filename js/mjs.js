$(document).ready(function(){
	xh();
});
function xh(){
	var div=$(".yd_bar_img");
	div.animate({marginLeft:'30px',opacity:'0'},1000);
	div.animate({marginLeft:'0px',opacity:'1'},3000);
	div.animate({marginLeft:'0px',opacity:'1'},1000,xh);//回调函数用法
}


