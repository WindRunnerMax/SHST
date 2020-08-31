"use strict";
import request from "@/modules/request";
import {toast} from "@/modules/toast";
import {extend} from  "@/modules/copy";
import {data} from "@/modules/global-data";
import {PubSub} from "@/modules/event-bus";
import {extDate} from "@/modules/datetime";
import {checkUpdate} from  "@/modules/update";
import {getCurWeek} from  "@/vector/pubFct";

function disposeApp($app){
    extDate(); //拓展Date原型
    checkUpdate(); // 检查更新
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
    var userInfo = uni.getStorageSync("user") || {};
    uni.login({
        scopes: "auth_base"
    }).then((data) => {
        var [err,res] = data;
        if(err) return Promise.reject(err);
        return $app.$scope.request({
            load: 3,
            // #ifdef MP-WEIXIN
            url: $app.data.url + "auth/wx",
            // #endif
            // #ifdef MP-QQ
            url: $app.data.url + "auth/QQ",
            // #endif
            method: "POST",
            data: {
                "code": res.code,
                user: JSON.stringify(userInfo)
            }
        })
    }).then((res) => {
        $app.data.curTerm = res.data.initData.curTerm;
        $app.data.curTermStart = res.data.initData.termStart;
        $app.data.curWeek = res.data.initData.curWeek;
        $app.data.initData = res.data.initData;
        if($app.data.initData.custom){
            let custom = $app.data.initData.custom;
            if(custom.color_list) {
                $app.data.colorList = JSON.parse(custom.color_list);
                $app.data.colorN = $app.data.colorList.length;
            }
        }
        /* res.data.status   1 已注册用户  2 未注册用户*/
        $app.data.userFlag = res.data.status === 1 ? 1 : 0;
        console.log("Status:" + ($app.data.userFlag === 1 ? "user Login" : "New user"));
        var notify = res.data.initData.tips;
        $app.data.tips = point;
        var point = uni.getStorageSync("point") || "";
        if (point !== notify) uni.showTabBarRedDot({ index: 2 });
        console.log("SetOpenid:" + res.data.openid);
        $app.data.openid = res.data.openid;
        return Promise.resolve(res);
    }).then((res) => {
        if (!res.data.initData || !res.data.initData.curTerm) return Promise.reject("DATA INIT FAIL");
        else $app.$scope.eventBus.commit("LoginEvent", res);
    }).catch((err) => {
        console.log(err);
        uni.showModal({
            title: "警告",
            content: "数据初始化失败,点击确定重新初始化数据",
            showCancel: false,
            success: (res) => onLaunch.apply($app)
        })
    })
}


export default {onLaunch, toast}