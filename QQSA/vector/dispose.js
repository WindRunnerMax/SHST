"use strict";
const md5 = require('../utils/md5.js');
const eventBus = require('../utils/eventBus.js');
module.exports = {
  ajax: ajax,
  toast: toast,
  extend: extend,
  userDot: userDot,
  onLunch: onLunch,
  setCookie: setCookie,
  endLoading: endLoading,
  checkUpdate: checkUpdate,
  startLoading: startLoading
}

/**
 * 颜色方案
 */
module.exports.colorList = ["#EAA78C", "#F9CD82", "#9ADEAD", "#9CB6E9", "#E49D9B", "#97D7D7", "#ABA0CA", "#9F8BEC", "#ACA4D5", "#6495ED", "#7BCDA5", "#76B4EF"];

/**
 * 拓展对象
 * 浅拷贝与深拷贝
 */
function extend(){
  var aLength = arguments.length;
  var options = arguments[0];
  var target = {};
  var copy;
  var i = 1;
  if (typeof options === "boolean" && options === true) {
    //深拷贝 (仅递归处理对象)
    for (; i < aLength; i++) {
      if ((options = arguments[i]) != null) {
        if (typeof options !== 'object') {
          return options;
        }
        for (var name in options) {
          copy = options[name];
          if (target === copy) {
            continue;
          }
          target[name] = this.extend(true, options[name]);
        }
      }
    }
  } else {
    //浅拷贝
    target = options;
    if (aLength === i) {
      target = this;
      i--;
    } //如果是只有一个参数，拓展功能 如果两个以上参数，将后续对象加入到第一个对象
    for (; i < aLength; i++) {
      options = arguments[i];
      for (var name in options) {
        target[name] = options[name];
      }
    }
  }
  return target;
}

/**
 * startLoading
 */
function startLoading(option) {
  switch (option.load) {
    case 1:
      wx.showNavigationBarLoading();
      break;
    case 2:
      wx.showNavigationBarLoading();
      wx.setNavigationBarTitle({
        title: '加载中...'
      })
      break;
    case 3:
      wx.showLoading({
        title: '请求中',
        mask: true
      })
      break;
  }
}

/**
 * endLoading
 */
function endLoading(option) {
  switch (option.load) {
    case 1:
      wx.hideNavigationBarLoading();
      break;
    case 2:
      wx.hideNavigationBarLoading();
      wx.setNavigationBarTitle({
        title: '山科小站'
      })
      break;
    case 3:
      wx.hideLoading();
      break;
  }
}

/**
 * 小程序更新
 */
function checkUpdate() {
  if (!wx.getUpdateManager) return false;
  wx.getUpdateManager().onCheckForUpdate((res) => {
    console.log("Update:" + res.hasUpdate);
    if (res.hasUpdate) { //如果有新版本
      wx.getUpdateManager().onUpdateReady(() => { //当新版本下载完成
        wx.showModal({
          title: '更新提示',
          content: '新版本已经准备好，单击确定重启应用',
          success: (res) => {
            if (res.confirm) wx.getUpdateManager().applyUpdate(); //applyUpdate 应用新版本并重启
          }
        })
      })
      wx.getUpdateManager().onUpdateFailed(() => { //当新版本下载失败
        wx.showModal({
          title: '提示',
          content: '检查到有新版本，但下载失败，请检查网络设置',
          showCancel: false
        })
      })
    }
  })
}

/**
 * User显示Dot
 */
function userDot() {
  if (!wx.showTabBarRedDot) return false;
  wx.getStorage({
    key: 'point',
    complete: (res) => {
      if (res.data !== getApp().globalData.tips) {
        wx.showTabBarRedDot({
          index: 2
        })
      }
    }
  })
}

/**
 * SetCookie
 */
function setCookie(res, option) {
  if (getApp().globalData.header.Cookie === "" && option.autoCookie) {
    if (res && res.header && res.header['Set-Cookie']) {
      var cookies = res.header['Set-Cookie'].split(";")[0] + ";";
      console.log("SetCookie:" + cookies);
      getApp().globalData.header.Cookie = cookies;
      wx.setStorageSync('cookies', getApp().globalData.header.Cookie);
    } else {
      console.log("Get Cookie From Cache");
      getApp().globalData.header.Cookie = wx.getStorageSync("cookies") || "";
    }
  }
}

/**
 * 弹窗提示
 */
function toast(e, time = 2000, icon = 'none'){
  wx.showToast({
    title: e,
    icon: icon,
    duration: time
  })
}

/**
 * XHR请求
 */
function ajax(requestInfo) {
  var option = {
    load: 1,
    autoCookie: true,
    url: "",
    method: "GET",
    data: {},
    fun: () => { },
    success: () => { },
    fail: function () { this.completeLoad = () => { toast("服务器错误"); }; },
    complete: () => { },
    completeLoad: () => { }
  };
  extend(option, requestInfo);
  startLoading(option);
  wx.request({
    url: option.url,
    data: option.data,
    method: option.method,
    header: getApp().globalData.header,
    success: (res) => {
      setCookie(res, option);
      try {
        option.fun(res);
        option.success(res);
      } catch (e) {
        option.completeLoad = () => { toast("PARSE ERROR"); }
        console.log(e);
      }
    },
    fail: (res) => {
      option.fail(res);
    },
    complete: function (res) {
      endLoading(option);
      option.complete(res);
      option.completeLoad(res);
    }
  })
}

/**
 * APP启动事件
 */
function onLunch(){
  const app = getApp();
  app.eventBus = eventBus.getEventBus;
  var canSend = wx.getStorageSync("cansend") || null;
  if(canSend == "false") return false;
  wx.login({
    success: res => {
      app.ajax({
        load: 1,
        url: app.globalData.url + 'qfunct/user/signalGetOpenid',
        method: 'POST',
        data: {
          "code": res.code,
          "type": typeof (qq) === "undefined" ? "1" : "2"
        },
        fun: function (data) {
          if (data.data.openid) {
            console.log("SetOpenid:" + data.data.openid);
            app.globalData.openid = data.data.openid;
            wx.setStorageSync('openid', data.data.openid);
          } else {
            console.log("Get Openid From Cache");
            app.globalData.openid = wx.getStorageSync("openid") || "";
          }
          data.data.Message === "Yes" ? app.globalData.canSend = "true" : app.globalData.canSend = "false";
          console.log("CANSEND:" + app.globalData.canSend);
          wx.setStorageSync("cansend", app.globalData.canSend);
        },
        complete: (data) => {

        }
      })
    }
  })
}