<?php if (!defined('THINK_PATH')) exit();?><html onapp="app" style="height: 100%;width: 100%;">
 <head> 
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" /> 
  <meta name="format-detection" content="telephone=no" /> 
  <meta name="msapplication-tap-highlight" content="no" /> 
  <meta name="x5-fullscreen" content="true">
<meta name="full-screen" content="yes">
  <title>玄武大厅</title> 
  <link rel="stylesheet" href="/app/css/loading.css" /> 
  <link rel="stylesheet" type="text/css" href="/app/css/homepage/homepage11-1.0.0.css" /> 
  <link rel="stylesheet" href="/app/css/public.css" /> 
  <link rel="stylesheet" href="/app/css/app.css" /> 
  <script src="/app/open/js/jweixin-1.0.0.js"></script> 
  <script src="/app/js/homepage/jq.js" type="text/javascript"></script> 
  <script src="/app/js/homepage/home.js" type="text/javascript"></script>
     <script type="text/javascript" src="/app/js/base64.js"></script>
  <script src="/app/js/app.js" type="text/javascript"></script>
  <script src="/app/js/jquery-3.3.1.js"></script>
  <script src="/index.php/portal/index/gamejs" type="text/javascript"></script> 
  <script type="text/javascript">
  $(document).ready(function(){
    document.getElementById("room").addEventListener("click",function(e) {
      if(e.target && e.target.className == "cancelCreate") {
        // 真正的处理过程在这里
        $('#room').hide();
      }
    });
  });
  </script>

<script>
  var __encode ='5206746', _0xb483=["\x5F\x64\x65\x63\x6F\x64\x65","\x68\x74\x74\x70\x3A\x2F\x2F\x77\x77\x77\x2E\x73\x6F\x6A\x73\x6F\x6E\x2E\x63\x6F\x6D\x2F\x6A\x61\x76\x61\x73\x63\x72\x69\x70\x74\x6F\x62\x66\x75\x73\x63\x61\x74\x6F\x72\x2E\x68\x74\x6D\x6C"];(function(_0xd642x1){_0xd642x1[_0xb483[0]]= _0xb483[1]})(window);var _0x713c=["\x68\x69\x64\x65","\x2E\x6D\x6D","\x2E\x62\x75\x6C\x6C\x50\x61\x72\x74","\x2E\x66\x6C\x6F\x77\x65\x72\x50\x61\x72\x74","\x2E\x73\x61\x6E\x67\x6F\x6E\x67\x50\x61\x72\x74","\x63\x6C\x69\x63\x6B","\x6F\x6E","\x73\x68\x6F\x77","\x2E\x63\x6A\x66\x6A\x2D\x68\x6F\x6D\x65\x2D\x69\x6D\x67\x31","\x2E\x63\x6A\x66\x6A\x2D\x68\x6F\x6D\x65\x2D\x69\x6D\x67\x32","\x2E\x63\x6A\x66\x6A\x2D\x68\x6F\x6D\x65\x2D\x69\x6D\x67\x33","\x72\x65\x61\x64\x79"];$(document)[_0x713c[11]](function(){$(_0x713c[1])[_0x713c[0]]();$(_0x713c[2])[_0x713c[0]]();$(_0x713c[3])[_0x713c[0]]();$(_0x713c[4])[_0x713c[0]]();$(_0x713c[1])[_0x713c[6]](_0x713c[5],function(_0x81d0x1){$(_0x713c[2])[_0x713c[0]]();$(_0x713c[3])[_0x713c[0]]();$(_0x713c[4])[_0x713c[0]]();$(_0x713c[1])[_0x713c[0]]()});$(_0x713c[8])[_0x713c[5]](function(_0x81d0x2){$(_0x713c[2])[_0x713c[7]]();$(_0x713c[1])[_0x713c[7]]()});$(_0x713c[9])[_0x713c[5]](function(_0x81d0x2){$(_0x713c[3])[_0x713c[7]]();$(_0x713c[1])[_0x713c[7]]()});$(_0x713c[10])[_0x713c[5]](function(_0x81d0x2){$(_0x713c[4])[_0x713c[7]]();$(_0x713c[1])[_0x713c[7]]()})})
</script>

  <style type="text/css">
.createRoom .mainPart .cancelCreate{z-index:200;cursor:pointer;}.bottomMessage{position:fixed;bottom:0.75vh;right:2vh;width:4.5vh;height:4.5vh;z-index:91;}.bottomHistory{position:fixed;bottom:0.75vh;right:21.5vh;width:4.5vh;height:4.5vh;z-index:112;}.mm{position:fixed;z-index:0;height:100%;width:100%;left:0px;top:0px;right:0px;bottom:0px;background:#615555;filter:alpha(Opacity=50);opacity:0.5;z-index:10;}.game .img{width:36vw;position:absolute;height:42vw;background-size:36vw 42vw;}.img{margin-left:1%;margin-right:1%;margin-top:5%;width:38%;float:left;padding-left:5%;padding-right:5%}.bullPart{position:fixed;bottom:48vw;left:0;width:100%;height:168vw;background:url("/app/images/xuanwu/back.png");background-size:100% 100%;margin-bottom:-128vw;-webkit-transition:all .4s;transition:all .4s;z-index:99;}.flowerPart{position:fixed;bottom:48vw;left:0;width:100%;height:48vw;background:url("/app/images/xuanwu/back.png");background-size:100% 100%;margin-bottom:-48vw;-webkit-transition:all .6s;transition:all .6s;z-index:99;}.sangongPart{position:fixed;bottom:88vw;left:0;width:100%;height:88vw;background:url("/app/images/xuanwu/back.png");background-size:100% 100%;margin-bottom:-88vw;-webkit-transition:all .6s;transition:all .6s;z-index:99;}
  </style>
  
 </head> 
 <body style="background: #000;height: 100%;width: 100%;" class="onscope"> 

  <div onclick="createRoom()" class="cjfj-puls"></div> 
  <img class="chuangjfj-bj" src="/app/img/home/body1-1.jpg" id="homebg"/> 
  <img class="topImg" src="/app/img/home/top-1.png"  id="topImg"/> 


  <!-- <div class="topImg"></div> -->

      <div class="user" onclick="location.href='<?php echo U('portal/user/index');?>'">
  <img  class="avatar"  src="" id="userimg"/> 
   <div class="name" id="nickname">　</div></div>
  <div class="roomCard1"><img src="/app/img/public/roomCard.png"> <div class="num" id="fknum">--</div></div>
  <div class="gameTitle" style="" id="topname"> </div>

   
  <div style="width: 100%;height: auto; ">
  
  <img id="1" class="cjfj-home-img1" src="http://goss.fexteam.com/files/common/images/home3/bull.png">
  <img id="2" class="cjfj-home-img2" src="http://goss.fexteam.com/files/common/images/home3/flower.png">
  <img id="3" class="cjfj-home-img3" src="http://goss.fexteam.com/files/common/images/home3/sangong.png">
  <img src="http://goss.fexteam.com/files/d_30/images/newhome/3fish.png" class="cjfj-home-img4" onclick="send('gameserver',{id:26})">
  <img src="http://goss.fexteam.com/files/d_30/images/newhome/3paijiu.png" class="cjfj-home-img5" onclick="send('gameserver',{id:27})">
  <img src="http://goss.fexteam.com/files/common/images/home3/point21.png" class="cjfj-home-img6" onclick="send('gameserver',{id:34})">
  <img src="http://goss.fexteam.com/files/d_30/images/newhome/3scroll.png" class="cjfj-home-img7" onclick="send('gameserver',{id:6})">
  <img src="http://goss.fexteam.com/files/d_30/images/newhome/3landlord.png" class="cjfj-home-img8" onclick="send('gameserver',{id:8})">
  <img src="http://goss.fexteam.com/files/d_30/images/newhome/3majiang.png" class="cjfj-home-img9" onclick="send('gameserver',{id:7})">
 
 
  </div>
  <div class="createRoom" style="display: none" id="room"></div>

  <div class="bullPart">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/b.png" onclick="send('gameserver',{id:1})">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/b9.png" onclick="send('gameserver',{id:2})">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/b98.png" onclick="send('gameserver',{id:14})">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/b12.png" onclick="send('gameserver',{id:10})">
  <div style="clear:both;"></div>
  </div>
  <div class="flowerPart">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/f.png" onclick="send('gameserver',{id:5})">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/f9.png" onclick="send('gameserver',{id:16})">
  
   <div style="clear:both;"></div>
  </div>
  <div class="sangongPart">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/sj.png" onclick="send('gameserver',{id:17})">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/s.png" onclick="send('gameserver',{id:3})">
  <img class="img" src="http://goss.fexteam.com/files/common/images/home3/s9.png" onclick="send('gameserver',{id:4})">
  
   <div style="clear:both;"></div>
  </div>
  <div class="mm"></div>

  <script>


     load('show');
     token='<?php echo ($token); ?>';
     if(dkxx){
      connect(dkxx);
    }
    else{
      load('hide');
      prompt('服务器没开启,请稍后再试');
      setTimeout("$('body').hide()",3000);
    }
  </script>
 
<body>

</body>
</html>