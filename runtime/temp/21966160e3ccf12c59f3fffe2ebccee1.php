<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"/www/wwwroot/www.open.edu-bo.top/app/adapt/view/sw/overview.html";i:1554885597;s:64:"/www/wwwroot/www.open.edu-bo.top/app/adapt/view/common/view.html";i:1554877305;}*/ ?>
﻿<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" >
<title>Query</title>

<link href="<?php echo $ctx; ?>/public/static/js/layui/css/layui.css" rel="stylesheet">
<link href="<?php echo $ctx; ?>/public/static/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo $ctx; ?>/public/static/css/styles.css" rel="stylesheet">

<script src="<?php echo $ctx; ?>/public/static/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo $ctx; ?>/public/static/js/bootstrap.min.js"></script>
<script src="<?php echo $ctx; ?>/public/static/js/layui/layui.js"></script>
<script src="<?php echo $ctx; ?>/public/static/js/vue.min.js"></script>
</head>
<style type="text/css">
  fieldset{padding:.3em .30em .10em;margin:0 2px;border:1px solid silver}
  legend{padding:10px;border:0;width:auto;margin-bottom:0px;}
</style>
<script type="text/javascript">
    var ctx = "<?php echo $ctx; ?>";
    var user = "<?php echo $user; ?>";
</script>
<body>

  <div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
      <div class="login-panel panel panel-default">
        <div class="panel-body" style='position:relative;'>
          用户：<?php echo $user; ?>
          <div class="layui-btn layui-btn-primary layui-btn-xs" style="padding: 0 11px;font-size: 12px;margin-top: -1px;position: absolute;right: 120px;" onclick="location.href='<?php echo $ctx; ?>/index/sw/overview'">兼容</div>
          <div class="layui-btn layui-btn-primary layui-btn-xs" style="padding: 0 11px;font-size: 12px;margin-top: -1px;position: absolute;right: 65px;" onclick="location.href='<?php echo $ctx; ?>/adapt/sw/overview'">菜单</div>
          <div class="layui-btn layui-btn-primary layui-btn-xs" style="padding: 0 11px;font-size: 12px;margin-top: -1px;position: absolute;right: 10px;" onclick="location.href='<?php echo $ctx; ?>/?status=E'">注销</div>
        </div>
      </div>
    </div><!-- /.col-->
  </div><!-- /.row --> 

  <div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
      <div class="login-panel panel panel-default">
        <div class="panel-heading">
总览
</div>
        <div class="panel-body">
          
<style type="text/css">
    .funct{
        border-bottom:1px solid #eee;
        border-right:1px solid #eee;
        width: 33.3%;
        text-align: center;
        cursor: pointer;
        padding-bottom: 9px;
    }
    .size{
        display: block;
        margin-top: 15px;
        font-size: 45px; 
        margin-bottom: 5px;
    }
</style>
<div style="display: flex;flex-wrap: wrap;border-left:1px solid #eee;border-top:1px solid #eee;">
<div class="funct" onclick="location.href='<?php echo $ctx; ?>/adapt/sw/table'"><i class="layui-icon layui-icon-table size"></i>查课表</div>
<div class="funct" onclick="location.href='<?php echo $ctx; ?>/adapt/sw/classroom'"><i class="layui-icon layui-icon-user size"></i>查教室</div>
<div class="funct" onclick="location.href='<?php echo $ctx; ?>/adapt/sw/grade'"><i class="layui-icon layui-icon-chart-screen size"></i>查成绩</div>
<div class="funct" onclick="location.href='https://github.com/WindrunnerMax/SW'"><i class="layui-icon layui-icon-fonts-code size"></i>源码</div>
<div class="funct" onclick="location.href='https://github.com/WindrunnerMax/SW/blob/master/ChangeLog.md'"><i class="layui-icon layui-icon-log size"></i>更新日志</div>
<div class="funct" onclick="location.href='<?php echo $ctx; ?>/adapt/sw/info'"><i class="layui-icon layui-icon-about size"></i>公告</div>
</div>
<script type="text/javascript">
    (function() {
        localStorage.setItem("flag","1");
    }())
</script>

        </div>
      </div>
    </div><!-- /.col-->
  </div><!-- /.row -->  

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
  <script type="text/javascript">
  function errmsg(msg,b={},width="200px"){
      layui.use('layer', function(){
            var layer=layui.layer;
            b.icon = 5;
            b.area = width;
            layer.msg(msg,b);
    })
  }
  function sucmsg(msg,b={},width="200px"){
      layui.use('layer', function(){
            var layer=layui.layer;
            b.icon = 6;
            b.area = width;
            layer.msg(msg,b);
    })
  }
  function loadmsg() {
    layui.use('layer', function(){
          var layer = layui.layer;
          layer.msg('请稍后', {
                  icon: 16
                  ,shade: 0.01
                  ,area: ['160px', '70px']
          });
    })   
  }
</script>
</body>
</html>

