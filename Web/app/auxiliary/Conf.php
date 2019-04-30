<?php
namespace app\auxiliary;

class Conf
{
    public static function getUrl(){
        return "http://192.168.113.130/app.do";
    }

    public static function getHeader(){
        return array(
        'User-Agent: Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer: http://www.baidu.com',
        'accept-encoding: gzip, deflate, br',
        'accept-language: zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control: max-age=0'
        );
    }

    public static function getTips(){
        return '<fieldset class="layui-elem-field">
                  <legend>公告</legend>
                  <div class="layui-field-box">
                   <div><span class="layui-badge-dot layui-bg-green"></span> 公网恢复访问 </div>
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
                </fieldset>'
        ;
    }
}