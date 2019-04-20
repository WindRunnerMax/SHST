<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"/www/wwwroot/www.open.edu-bo.top/app/index/view/index/index.html";i:1554982245;s:66:"/www/wwwroot/www.open.edu-bo.top/app/index/view/common/layout.html";i:1552660289;}*/ ?>
﻿<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" >
<title>Query</title>

<link href="<?php echo $ctx; ?>/public/static/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $ctx; ?>/public/static/css/styles.css" rel="stylesheet">
</head>
<style type="text/css">
  body{
    margin-top: 50px;
  }
</style>
<body>
  <div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
      <div class="login-panel panel panel-default">
        <div class="panel-heading">
Log In
</div>
        <div class="panel-body">
          
<script type="text/javascript">
  
</script>
<form id="formAct" action="<?php echo $ctx; ?>/index/sw/login" method="post">
      <input id="user1" name="username" type="hidden" required>
      <input id="password1" name="password" type="hidden" required>
      <input id="flag1" name="flag" type="hidden" required>
</form>
<script type="text/javascript">
  function setCookie(name, value) {
    var Days = 180;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
    var cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + "; path=/";
    document.cookie = cookie;
  }

  function getCookie(name) {
      var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
      if (arr = document.cookie.match(reg)) return unescape(arr[2]);
      else return null;
  }

  function delCookie(name) {
      var exp = new Date();
      exp.setTime(exp.getTime() - 1);
      var cval = getCookie(name);
      if (cval != null) document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
  }
  function login1() {
    document.getElementById('formAct').submit();
  }
  (function() {
    if(localStorage.getItem('flag') === null) localStorage.setItem("flag","0");
    if( "<?php echo $status; ?>"!=="E" && localStorage.getItem("user")!=null && localStorage.getItem('password')!=null){
        document.getElementById('user1').value=localStorage.getItem("user");
        document.getElementById('password1').value=localStorage.getItem("password");
        document.getElementById('flag1').value=localStorage.getItem("flag");
        login1();
    }
  })();
</script>
<form role="form" action="<?php echo $ctx; ?>/index/sw/login" id="form" method="post">
  <fieldset>
    <div class="form-group">
      <input class="form-control" placeholder="账号" id="user" name="username" type="text" required>
    </div>
    <div class="form-group">
      <input class="form-control" placeholder="密码" id="password" name="password" type="password" required>
    </div>
      <input id="flag" name="flag" type="hidden" required>
    <center>
      <button  class="btn btn-primary" onclick="subSave()" >保存并登录</button> 
    </center>
  </fieldset>
</form>
<script type="text/javascript">
  (function() {
    if(localStorage.getItem('flag') !== null) {document.getElementById('flag').value=localStorage.getItem("flag");}
    if(localStorage.getItem("user")!=null && localStorage.getItem('password')!=null){
        document.getElementById('user').value=localStorage.getItem("user");
        document.getElementById('password').value=localStorage.getItem("password");
    }
  })();

  function login() {
    document.getElementById('form').submit();
  }


function subSave(){
    localStorage.setItem("user",document.getElementById("user").value);
    localStorage.setItem("password",document.getElementById("password").value);
    login();
}


</script>

        </div>
      </div>
    </div><!-- /.col-->
  </div><!-- /.row -->  
  
    

<script src="<?php echo $ctx; ?>/public/static/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo $ctx; ?>/public/static/js/bootstrap.min.js"></script>

  <script>
    !function ($) {
      $(document).on("click","ul.nav li.parent > a > span.icon", function(){      
        $(this).find('em:first').toggleClass("glyphicon-minus");    
      }); 
      $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
    }(window.jQuery);

    $(window).on('resize', function () {
      if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
    })
    $(window).on('resize', function () {
      if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
    })
  </script> 
</body>
</html>

