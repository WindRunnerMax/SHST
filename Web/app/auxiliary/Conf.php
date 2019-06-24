<?php
namespace app\auxiliary;
use think\Db;
use think\Log;

class Conf
{   
    public static function getCtx(){
      return "/sdust";
    }

    public static function getCurTerm(){
        return "2018-2019-2";
    }

    private static function getCurTermStart(){
        return "2019-02-25";
    }

    public static function getCurWeek(){
        $d1 = strtotime("2019-2-25");
        $d2 = ceil((time()-$d1)/60/60/24) - 1;
        return (int)($d2/7)+1;
    }

    public static function getUrl(){
        return "http://219.218.128.228/app.do";
    }

    public static function getHeader(){  //固定一个Cookie请求
        return array(
        "Expect: ",
        'User-Agent: Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer: http://www.baidu.com',
        'accept-encoding: deflate, br',
        'accept-language: zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control: max-age=0',
        'Cookie: JSESSIONID=4045B439B3104A7A9CB2DF01D20324DF'
        );
    }

    public static function libGetHeader(){ //图书馆用到了Cookie特殊对待
        return array(
        "Expect: ",
        'User-Agent: Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer: http://www.baidu.com',
        'accept-encoding: deflate, br',
        'accept-language: zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control: max-age=0'
        );
    }

    public static function getNormalHeader(){ //图书馆用到了Cookie特殊对待
        return array(
        "Expect: ",
        'User-Agent: Mozilla/5.0 (Linux; U; Mobile; Android 6.0.1;C107-9 Build/FRF91 )',
        'Referer: http://www.baidu.com',
        'accept-encoding: deflate, br',
        'accept-language: zh-CN,zh-TW;q=0.8,zh;q=0.6,en;q=0.4,ja;q=0.2',
        'cache-control: max-age=0'
        );
    }

    public static function getTips(){
        return '<fieldset class="layui-elem-field">
                  <legend>公告</legend>
                  <div class="layui-field-box">
		    <div><span class="layui-badge-dot layui-bg-green"></span> 微信小程序 山科小站 正式上线  <a href="https://www.liyanzuisha.cn/sdust/index.php/index/index/info">点我查看详情</a></div>
		    <div><span class="layui-badge-dot layui-bg-green"></span> 修复因课重复而导致课表被覆盖的问题 </div>
		    <div><span class="layui-badge-dot layui-bg-green"></span> 增加近三天天气功能，点击图标查看今天天气状态 </div>
                    <div><span class="layui-badge-dot layui-bg-green"></span> 由于离线课表功能只有在兼容模式下才能正常使用，最终考虑取消了标准模式 </div>
		  </div>
                </fieldset>
                '
        ;
    }

    public static function getColorList(){
        return ["#EAA78C","#F9CD82","#9ADEAD","#9CB6E9","#E49D9B","#97D7D7","#ABA0CA","#9F8BEC","#ACA4D5","#6495ED","#7BCDA5","#76B4EF"];
    }

    public static function getNewTips(){
        return 9;
    }
}
