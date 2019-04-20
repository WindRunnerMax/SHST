<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"/www/wwwroot/www.open.edu-bo.top/app/index/view/sw/grade.html";i:1552193360;s:64:"/www/wwwroot/www.open.edu-bo.top/app/index/view/common/view.html";i:1554876742;}*/ ?>
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
          <div class="layui-btn layui-btn-primary layui-btn-xs" style="padding: 0 11px;font-size: 12px;margin-top: -1px;position: absolute;right: 120px;" onclick="location.href='<?php echo $ctx; ?>/adapt/sw/overview'">标准</div>
          <div class="layui-btn layui-btn-primary layui-btn-xs" style="padding: 0 11px;font-size: 12px;margin-top: -1px;position: absolute;right: 65px;" onclick="location.href='<?php echo $ctx; ?>/index/sw/overview'">菜单</div>
          <div class="layui-btn layui-btn-primary layui-btn-xs" style="padding: 0 11px;font-size: 12px;margin-top: -1px;position: absolute;right: 10px;" onclick="location.href='<?php echo $ctx; ?>/?status=E'">注销</div>
        </div>
      </div>
    </div><!-- /.col-->
  </div><!-- /.row --> 

  <div>
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
      <div class="login-panel panel panel-default">
        <div class="panel-heading">
成绩
</div>
        <div class="panel-body">
          
<center style="display: flex;justify-content:center;">
    <form class="layui-form" action="">
        <div class="layui-form-item" style="margin-right: 5px;border-radius: 3px;width: 150px;">
            <select id="sele" class="layui-input layui-unselect" >
                <option value="">全部学期</option>
                <option value="2016-2017-1">2016-2017-1</option>
                <option value="2016-2017-2">2016-2017-2</option>
                <option value="2017-2018-1">2017-2018-2</option>
                <option value="2017-2018-2">2017-2018-1</option>
                <option value="2018-2019-1">2018-2019-1</option>
                <option value="2018-2019-2">2018-2019-2</option>
                <option value="2019-2020-1">2019-2020-1</option>
                <option value="2019-2020-2">2019-2020-2</option>
                <option value="2020-2021-1">2020-2021-1</option>
                <option value="2020-2021-2">2020-2021-2</option>
            </select>
        </div>
    </form>
    <div class="layui-btn layui-btn-normal" onclick="loadGrade()">确定</div>
</center>
<hr class="layui-bg-gray">
    <div id="app" style="display: none;">
        <div id="point" style="text-align: center;"></div>
        <div v-for="item in info">
            <div style="padding: 6px;">{{item.zcj}} {{item.kcmc}} {{item.xf}}</div>
            <hr class="layui-bg-gray">
        </div>
    </div>
</div>

<script type="text/javascript">
    function init() {
        ve = new Vue({
          el: '#app',
          data: {
              info:[]
          }
        });
        layui.use('form', function(){
          var form = layui.form;
        });
    }
    
    function vueLoad(info){
        layer.closeAll();
        if(info[0] === null) {alert("无成绩信息");return ;}
        console.log(info);
        pointHandle(info);
        $("#app").css("display","block");
        ve.$data.info = info;
    }
    function loadGrade() {
        loadmsg();
        var xn = $("#sele").val();
        if (xn !== "") xn = "/" + xn;
        $.ajax({                                      
          type:"post",
          url:ctx + "/funct/sw/grade" + xn,  
          dataType:"json",                      
          success:function(data)
          {
              if (data.MESSAGE=="Yes") vueLoad(data.data);
              else errmsg('请求失败');
          },
          error:function()
          {
              layer.closeAll();
              errmsg('请求失败');
          }
      });
    }

    function pointHandle(info){
      try{
        var n = info.length;
        var point = 0;
        var pointN = 0;
        for (var i = 0; i < n; i++) {
        	if (info[i].kclbmc === "公选" ) continue;
        	pointN++;
            if (info[i].zcj === "优") point+=4.5;
            else if(info[i].zcj === "良") point+=3.5;
            else if(info[i].zcj === "中") point+=2.5;
            else if(info[i].zcj === "及格") point+=1.5;
            else if(info[i].zcj === "不及格") point+=0;
            else {
                var s = parseInt(info[i].zcj);
                if (s>=60) point += ((s-50) / 10);
            }
            $("#point").html("当前成绩绩点:"+ (point/pointN).toFixed(2) + "<hr class='layui-bg-gray'>");
        }
      }catch(err){}
        
    }
    window.onload=function(){$("#sele  option[value='<?php echo $xnxqh; ?>'] ").attr("selected",true);init();}
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

