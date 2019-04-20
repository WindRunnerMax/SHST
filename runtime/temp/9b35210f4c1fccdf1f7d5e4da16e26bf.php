<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"/www/wwwroot/www.yike.com/app/adapt/view/sw/info.html";i:1555756636;s:57:"/www/wwwroot/www.yike.com/app/adapt/view/common/view.html";i:1554877305;}*/ ?>
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
关于
</div>
        <div class="panel-body">
          
<fieldset class="layui-elem-field">
  <legend>公告</legend>
  <div class="layui-field-box">
   <div><span class="layui-badge-dot layui-bg-green"></span> 学校依然没有开启公网访问，采用了其他方法，稳定性差一些，如有问题可联系我 </div>
</fieldset>
<fieldset class="layui-elem-field">
  <legend>标准模式与兼容模式</legend>
  <div class="layui-field-box">
   <div><span class="layui-badge-dot layui-bg-green"></span> 标准模式可以适应大部分手机使用，异步执行，用户体验好</div>
   <div><span class="layui-badge-dot layui-bg-green"></span> 兼容模式是为了适应某些不兼容的手机，如果你无法查看课表的话，请改用兼容模式</div>
  </div>
</fieldset>
<fieldset class="layui-elem-field">
  <legend>关于</legend>
  <div class="layui-field-box">
   <div><span class="layui-badge-dot layui-bg-green"></span> 最早是因为智校园无法使用，我无法查询自习室，在J7爬了四层楼未找到自习室，遂回宿舍封装了此代码</div>
   <div><span class="layui-badge-dot layui-bg-green"></span> 
   如果有同学有想法拓展功能的话，源码提交在Github，可直接pull request，也可以联系我 QQ 651525974</div>
    <div><span class="layui-badge-dot layui-bg-green"></span> 欢迎 star fork</div>
  </div>
</fieldset>


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

