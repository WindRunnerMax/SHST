<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"/www/wwwroot/www.open.edu-bo.top/app/adapt/view/sw/table.html";i:1554882125;s:64:"/www/wwwroot/www.open.edu-bo.top/app/adapt/view/common/view.html";i:1554877305;}*/ ?>
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
查课表
</div>
        <div class="panel-body">
          
<style type="text/css">
	.info{
		margin-left:7px; 
	}
	.hrTable{
		border-bottom: 1px solid #eee;
		height: 133px; 
	}
	.hrTable2{
		border-bottom: 1px solid #eee;
		height: 136px; 
	}
	.hrTable3{
		border-bottom: 1px solid #eee;
		height: 137px; 
	}
	.hrTable4{
		border-bottom: 1px solid #eee;
		height: 136px; 
	}
	.hrTable5{
                border-bottom: 1px solid #eee;
                height: 136px;
        }
	.layui-bg-gray{
		margin-bottom: 3px;
	}
	.nl{

	}
</style>
<center style="display: flex;justify-content:center;">
	<div class="layui-btn layui-btn-primary layui-btn-sm nl" onclick="descZC()"><</div>
	<div style="margin-top: 6px;margin-left: 20px;margin-right: 20px;">第<label id="zcS"><?php echo $zc; ?></label>周</div><input type="hidden" id="zc" value="<?php echo $zc; ?>">
	<div class="layui-btn layui-btn-primary layui-btn-sm nl" onclick="addZC()">></div>
</center>
<hr class="layui-bg-gray">
 <div style="position: relative;">
 	<div class="hrTable"></div>
 	<div class="hrTable2"></div>
 	<div class="hrTable3"></div>
 	<div class="hrTable4"></div>
    <div class="hrTable5"></div>
    <div id="app"></div>
<?php if($info){ foreach ($info as $value) { ?>
	<div style="background: #eee;border-radius: 3px;position: absolute;
		height: <?php echo ( (int)substr($value['kcsj'],3,4) - (int)substr($value['kcsj'],1,2) ) * 130 ?>px;
		top: <?php echo ((int)substr($value['kcsj'],1,2) - 1)  * 68 ?>px;
		left: calc(100% / 7 * <?php echo ((int)$value['kcsj'][0] - 1); ?> + <?php echo ((int)$value['kcsj'][0]*3); ?>px);
		width: calc(100% / 7);"><center><div><?php echo $value['kcmc']; ?></div><div><?php echo $value['jsmc']; ?></div></center>
	</div>
<?php } } ?>
</div>

<script type="text/javascript">
	function addZC() {
		var zc = parseInt($("#zc").val()) + 1;
		location.href = '<?php echo $ctx; ?>/adapt/sw/table/'+zc;
	}
	function descZC() {
		var zc = parseInt($("#zc").val()) - 1;
		if (zc<0) {return ;}
		location.href = '<?php echo $ctx; ?>/adapt/sw/table/'+zc;
	}
	//window.onload=function(){loadTable();}
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

