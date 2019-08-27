// pages/User/Reward/rewardList.js
"use strict";
const app = getApp()
Page({
  onLoad: function (options) {
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + 'funct/ext/rewardlist',
      fun: res => {
        if (res.data.info) {
          res.data.info.reverse();
          that.setData({
            data: res.data.info
          })
        }
      }
    })
  }
})