<?php
namespace app\auxiliary;
use think\Db;
use think\Log;

class Conf
{
    public static function getUrl(){
        return "http://219.218.128.228/app.do";
    }

    public static function getHeader(){
        return array(
        "Expect: ",
        'User-Agent: Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer: http://www.baidu.com',
        'accept-encoding: gzip, deflate, br',
        'accept-language: zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control: max-age=0'
        );
    }

    public static function getCtx(){
      return "/sdust";
    }

    public static function getTips(){
        return '<fieldset class="layui-elem-field">
                  <legend>公告</legend>
                  <div class="layui-field-box">
                    <div><span class="layui-badge-dot layui-bg-green"></span> 由于离线课表功能只有在兼容模式下才能正常使用，最终考虑取消了标准模式</a></div>
                </fieldset>
                '
        ;
    }

    public static function getColorList(){
        return ["#EAA78C","#F9CD82","#9ADEAD","#9CB6E9","#E49D9B","#97D7D7","#ABA0CA","#6495ED"];
    }

    public static function getNewTips(){
        return 6;
    }
}
