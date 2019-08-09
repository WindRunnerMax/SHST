"use strict";
const dispose = require('/vector/dispose.js');
App({
  globalData: {
    userFlag: 0,
    host: 'https://www.liyanzuishuai.top',
    url: 'https://www.liyanzuishuai.top/sdust/index.php/',
    // url: 'https://www.michalingshi.cn/sdust/index.php/',
    header: {
      'Cookie': '',
      'content-type': 'application/x-www-form-urlencoded'
    },
    openid: "",
    version: "1.1.0",
    canSend : "false" 
  },
  extend: dispose.extend,
  onPageNotFound(res) { //处理404
    wx.reLaunch({
      url: 'pages/Home/NotFound/notfound'
    })
  }
})

const app = getApp();
const time = require('/vector/time.js');
time.extDate(); //拓展Date原型
dispose.onLunch(); //启动加载事件
dispose.checkUpdate(); //小程序更新

//拓展app功能
app.extend({
  toast: dispose.toast,
  ajax: dispose.ajax
});
