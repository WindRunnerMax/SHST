"use strict";
import request from "@/modules/request";
import {toast} from "@/modules/toast";
import {extend} from  "@/modules/copy";
import {data} from "@/modules/global-data";
import {PubSub} from "@/modules/event-bus";
import {extDate} from "@/modules/datetime";
import {checkUpdate} from  "@/modules/update";
import {getCurWeek} from  "@/vector/pubFct";
import {addIconfont} from  "@/utils/wex.js";

function disposeApp($app){
    extDate(); //拓展Date原型
    checkUpdate(); // 检查更新
    addIconfont(); // 添加字体文件
    $app.$scope = $app;
    uni.$app = $app.$scope;
    $app.$scope.toast = toast;
    $app.$scope.extend = extend;
    $app.$scope.eventBus = new PubSub();
    $app.data = $app.globalData;
    $app.$scope.data = $app.data;
    $app.$scope.extend($app.data, data);
    $app.$scope.extend($app.$scope, request);
    $app.data.colorN = $app.data.colorList.length;
    $app.data.curWeek = getCurWeek($app.data.curTermStart);
    $app.$scope.onload = (funct, ...args) => {
        if($app.data.openid) funct(...args);
        else $app.$scope.eventBus.on("LoginEvent", funct);
    }
}

/**
 * APP启动事件
 */
function onLaunch() {
    var $app = this;
    disposeApp(this);


    $app
}


export default {onLaunch, toast}
