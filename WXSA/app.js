"use strict";
const dispose = require('/vector/dispose.js');
App({
  globalData: {
    userFlag: 2, // 0 未登录 1 已登陆 2 加载中
    host: 'https://www.liyanzuishuai.top',
    url: 'https://www.liyanzuishuai.top/sdust/index.php/',
    header: {
      'Cookie': '',
      'content-type': 'application/x-www-form-urlencoded'
    },
    openid: "",
    colorList: dispose.colorList,
    version: "2.9.2",
    tips: "1",
    curTerm: "2019-2020-1",
    curTermStart: "2019-08-26"
  },
  extend: dispose.extend,
  onPageNotFound(res) { //处理404
    wx.reLaunch({
      url: 'pages/Home/NotFound/notfound'
    })
  },
  onLaunch(){
    console.log("APP INIT");
    dispose.onLunch.apply(this); //启动加载事件
  }
})

const app = getApp();
const time = require('/vector/time.js');
time.extDate(); //拓展Date原型
dispose.checkUpdate(); //小程序更新
app.globalData.colorN = app.globalData.colorList.length;
app.globalData.curWeek = time.getCurWeek(app.globalData.curTermStart);

//拓展app功能
app.extend({
  toast: dispose.toast,
  ajax: dispose.ajax
});
