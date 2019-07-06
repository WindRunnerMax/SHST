//app.js
"use strict";
App({
  globalData: {
    userFlag: 0,
    // url: 'http://localhost/Swisdom/Web/index.php/',
    // url: 'https://www.liyanzuisha.cn/sdust/index.php/',
    url: 'https://www.michalingshi.cn/sdust/index.php/',
    header: {
      'Cookie': '', //PHPSESSID
      'content-type': 'application/x-www-form-urlencoded'
    },
    openid: "",
    colorList: ["#EAA78C", "#F9CD82", "#9ADEAD", "#9CB6E9", "#E49D9B", "#97D7D7", "#ABA0CA", "#9F8BEC", "#ACA4D5", "#6495ED", "#7BCDA5", "#76B4EF"],
    version: "2.4.6",
    curTerm: "2018-2019-2",
    curTermStart: "2019-02-25"
  },
  extend: function() {
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
  },
  onPageNotFound(res) { //处理404
    wx.reLaunch({
      url: '/pages/index/index'
    })
  }
})

const app = getApp();
const time = require('/vector/time.js');
time.extDate(); //拓展Date原型
app.globalData.colorN = app.globalData.colorList.length;
app.globalData.curWeek = time.getCurWeek(app.globalData.curTermStart);

//拓展app功能
app.extend({

  /**
   * 弹窗
   */
  toast: function(e, time = 2000, icon = 'none') {
    wx.showToast({
      title: e,
      icon: icon,
      duration: time
    })
  },

  /**
   * 封装请求
   */
  ajax: function(requestInfo) {
    var option = {
      load: 1,
      cookie: true ,
      url: "",
      method: "GET",
      data: {},
      fun: () => {},
      success: () => {},
      fail: function () { this.completeLoad = () => { app.toast("服务器错误"); };},
      complete: () => {},
      completeLoad: () => {}
    };
    app.extend(option, requestInfo);
    switch (option.load) { //开启LOADING
      case 1:
        wx.showNavigationBarLoading();
        break;
      case 2:
        wx.showNavigationBarLoading();
        wx.setNavigationBarTitle({ title: '加载中...' })
        break;
      case 3:
        wx.showLoading({ title: '请求中', mask: true })
        break;
    }
    var hideLoad = () => { //关闭LOADING
      switch (option.load) {
        case 1:
          wx.hideNavigationBarLoading();
          break;
        case 2:
          wx.hideNavigationBarLoading();
          wx.setNavigationBarTitle({ title: '山科小站' })
          break;
        case 3:
          wx.hideLoading();
          break;
      }
    }
    wx.request({
      url: option.url,
      data: option.data,
      method: option.method,
      header: app.globalData.header,
      success: (res) => {
        try {
          if (app.globalData.header.Cookie === "" && option.cookie) {
            if (res && res.header && res.header['Set-Cookie']) {
              console.log("SetCookie");
              app.globalData.header.Cookie = res.header['Set-Cookie'].split(";")[0];
              wx.setStorage({
                key: 'phpsessid',
                data: app.globalData.header.Cookie
              })
            } else {
              console.log("Get Cookie From Cache");
              wx.getStorage({
                key: 'phpsessid',
                success: res => {
                  app.globalData.header.Cookie = res.data;
                }
              })
            }
          }
          option.fun(res);
          option.success(res);
        } catch (e) {
          option.completeLoad = () => { app.toast("PARSE ERROR"); }          
          console.log(e);
        }
      },
      fail: (res) => {
        option.fail(res);
      },
      complete: function(res) {
        hideLoad();
        option.complete(res);
        option.completeLoad(res);
      }
    })
  }

});


