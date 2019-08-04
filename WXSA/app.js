"use strict";
App({
  globalData: {
    userFlag: 0,
    url: 'https://www.liyanzuishuai.top/sdust/index.php/',
    // url: 'https://www.michalingshi.cn/sdust/index.php/',
    host: 'https://www.liyanzuishuai.top' ,
    header: {
      'Cookie': '',
      'content-type': 'application/x-www-form-urlencoded'
    },
    openid: "",
    colorList: ["#EAA78C", "#F9CD82", "#9ADEAD", "#9CB6E9", "#E49D9B", "#97D7D7", "#ABA0CA", "#9F8BEC", "#ACA4D5", "#6495ED", "#7BCDA5", "#76B4EF"],
    version: "2.7.3",
    tips: "1",
    curTerm: "2019-2020-1",
    curTermStart: "2019-08-26"
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
      url: 'pages/Home/NotFound/notfound'
    })
  }
})

const app = getApp();
const time = require('/vector/time.js');
const dispose = require('/vector/dispose.js');
time.extDate(); //拓展Date原型
dispose.checkUpdate(); //更新
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
      autoCookie: true ,
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
    dispose.startLoading(option);
    wx.request({
      url: option.url,
      data: option.data,
      method: option.method,
      header: app.globalData.header,
      success: (res) => {
        dispose.setCookie(res, option);
        try {
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
        dispose.endLoading(option);
        option.complete(res);
        option.completeLoad(res);
      }
    })
  }

});


