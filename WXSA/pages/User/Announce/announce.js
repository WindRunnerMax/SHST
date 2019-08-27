// pages/User/announcement/announcement.js
"use strict";
const app = getApp()
Page({
  copy(e) {
    wx.setClipboardData({
      data: e.currentTarget.dataset.copy
    })
  },
  onLoad: function (options) {
    wx.setStorage({
      key: 'point',
      data: app.globalData.tips
    })
    var that = this;
    app.ajax({
      load:2,
      url: app.globalData.url + 'funct/ext/announce',
      fun: res =>{
        if(res.data.info){
          res.data.info.reverse();
          that.setData({
            data: res.data.info
          })
        }
      }
    })
  }
})