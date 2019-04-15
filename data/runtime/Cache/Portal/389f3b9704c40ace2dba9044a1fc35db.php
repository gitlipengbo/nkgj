<?php if (!defined('THINK_PATH')) exit();?><html>
 <head> 
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" /> 
  <meta name="format-detection" content="telephone=no" /> 
  <meta name="msapplication-tap-highlight" content="no" /> 
  <title><?php echo ($titlexx); ?>主页</title> 
  <link rel="stylesheet" href="/themes/game/Public/css/public.css" /> 
  <link rel="stylesheet" href="/themes/game/Public/css/alert.css" /> 
  <link rel="stylesheet" href="/themes/game/Public/css/swiper-3.4.2.min.css" /> 
  <link rel="stylesheet" type="text/css" href="/themes/game/Public/css/bull_vue-1.0.0.css" /> 
  <link rel="stylesheet" type="text/css" href="/themes/game/Public/css/bullalert.css" /> 
  <link rel="stylesheet" type="text/css" href="/themes/game/Public/css/bullshop.css" /> 
  <link rel="stylesheet" type="text/css" href="/themes/game/Public/css/common/alert.css" /> 
  <link rel="stylesheet" type="text/css" href="/themes/game/Public/css/activity.css">
  <script src="/themes/game/Public/js/homepage/jq.js" type="text/javascript"></script>  
  <script src="/themes/game/Public/js/homepage/home.js" type="text/javascript"></script>  
  <script src="/themes/game/Public/js/swiper-3.4.2.min.js" type="text/javascript"></script> 
 </head> 
 <body style="background-color: #0e0226"> 
  



 <div id="fasongfk" style="background: rgb(14, 2, 38);height: 100%;position: fixed; width: 100%;">

<div class="main">
	
	<img src="/themes/game/Public/img/redpackage/redpackage_bg.jpg" class='fangk-1'  /> 
	
	<div class='fangk-2'>
		<p class='fangk-2-p'>你的房卡：<?php echo ($user["fk"]); ?> 张</p>
	</div>
	<div class='fangk-3'>
		<form  id="fangka_houxu" action="" method ="post">
		    <label class='fangk-3-label'>放入房卡</label>
			<input class='fangk-3-input' id="pnumber" type="number" value='' name="number" placeholder='0' 
        onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"  
        onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'0')}else{this.value=this.value.replace(/\D/g,'')}" ></input>
			<label  class='fangk-3-input2'>张</label>
		</form>
	</div>
		<div class='fangk-4' >
        <p class='fangk-4-p'><span class='fangk-4-span' >0</span><span class='fangk-4-span2'>张</span></p>
	</div>

    <!-- <div class='fangk-5' onclick="opnemm('fangka-houxu','fasongfking')"> -->
    <div class='fangk-5' onclick="shengchengfangka();">
    <p  class='fangk-5-p'>制作房卡</p>
    </div>
	<div class='fangk-6' >
		<p class='fangk-6-p'></p>
	</div>
	<div class='fangk-7' onclick="location.href='<?php echo U('portal/user/fangka_jl');?>'">
		<p class='fangk-7-p'>我的房卡记录</p>
	</div>
</div>


 </div>

<?php $jsonuser = json_encode($user); ?>
 </body>
 <script>

var user= <?php echo ($jsonuser); ?>;
 $('#pnumber').bind('input propertychange', function() {  
   var num = $("#pnumber").val();
   if(num ==''){
    num =0;
   }
   else if(parseInt(num)<0){
    num =0;
   }
   else if(parseInt(num)>parseInt(user.fk)){
    num =user.fk;
   }
   $('.fangk-4-span').html(num);
   $('#pnumber').val(num);
}); 

function shengchengfangka(){
  var number = $('#pnumber').val();
  if(number==''){
    alert('请输入房卡数量');
    return false;
  }
  var updataimgurl = '/index.php/portal/user/shengchengfangka';
       $.ajax({
         type:"post",
         data:{ number:number},
         url:updataimgurl,
         dataType: "json",
         success: function(suc){
          console.log(suc);
           if(suc.act=='1'){
            location.href = '/index.php/portal/user/fangka_houxu/mis/'+suc.msg;
           }else{
            if(!suc.msg){
              alert(suc.info);
            }
            else{
            alert(suc.msg);
            }
           }
         }
   });

 }
  
var mySwiper = new Swiper('.swiper-container', {
  slidesPerView : 5,
  freeMode : true,
})
</script>
</html>