"use strict";
import globalData from "@/modules/global-data";
import request from "@/modules/request";
import {toast} from "@/modules/toast";
import {extend} from  "@/modules/copy";
import {PubSub} from "@/modules/event-bus";
import {extDate} from "@/modules/datetime";
import {checkUpdate} from  "@/modules/update";
import {getCurWeek} from  "@/vector/pubFct";

function disposeApp(app){
    extDate(); //拓展Date原型
    checkUpdate(); // 检查更新
    app.$scope.toast = toast;
    app.$scope.extend = extend;
    app.$scope.eventBus = new PubSub();
    app.$scope.extend(app.$scope, request);
    app.$scope.extend(app.globalData, globalData);
    app.globalData.colorN = app.globalData.colorList.length;
    app.globalData.curWeek = getCurWeek(app.globalData.curTermStart);
}

/**
 * APP启动事件
 */
function onLaunch() {
    var app = this;
    disposeApp(this);
    var userInfo = uni.getStorageSync("user") || {};
    uni.login({
        scopes: 'auth_base'
    }).then((data) => {
        var [err,res] = data;
        if(err) return Promise.reject(err);
        return app.$scope.request({
            load: 3,
            // #ifdef MP-WEIXIN
            url: app.globalData.url + 'auth/wx',
            // #endif
            // #ifdef MP-QQ
            url: app.globalData.url + 'auth/QQ',
            // #endif
            method: 'POST',
            data: {
                "code": res.code,
                user: JSON.stringify(userInfo)
            }
        })
    }).then((res) => {
        app.globalData.curTerm = res.data.initData.curTerm;
        app.globalData.curTermStart = res.data.initData.termStart;
        app.globalData.curWeek = res.data.initData.curWeek;
        app.globalData.loginStatus = res.data.Message;
        app.globalData.initData = res.data.initData;
        if(app.globalData.initData.custom){
            let custom = app.globalData.initData.custom;
            if(custom.color_list) {
                app.globalData.colorList = JSON.parse(custom.color_list);
                app.globalData.colorN = app.globalData.colorList.length;
            }
        }
        if (res.data.Message === "Ex") app.globalData.userFlag = 1;
        else app.globalData.userFlag = 0;
        console.log("Status:" + (app.globalData.userFlag === 1 ? "User Login" : "New User"));
        if (res.data.openid) {
            var notify = res.data.initData.tips;
            app.globalData.tips = notify;
            var point = uni.getStorageSync("point") || "";
            if (point !== notify) uni.showTabBarRedDot({ index: 2 });
            console.log("SetOpenid:" + res.data.openid);
            app.globalData.openid = res.data.openid;
            uni.setStorageSync('openid', res.data.openid);
        } else {
            console.log("Get Openid From Cache");
            app.globalData.openid = uni.getStorageSync("openid") || "";
        }
        return Promise.resolve(res);
    }).then((res) => {
        if (res.statusCode !== 200 || !res.data.initData || !res.data.initData.curTerm)  return Promise.reject("DATA INIT FAIL");
        else app.$scope.eventBus.commit('LoginEvent', res);
    }).catch((err) => {
        console.log(err);
        uni.showModal({
            title: '警告',
            content: '数据初始化失败,点击确定重新初始化数据',
            showCancel: false,
            success: (res) => {
                if (res.confirm) onLaunch.apply(app);
            }
        })
    })
}


export default {onLaunch, toast}