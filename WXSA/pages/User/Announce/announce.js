// pages/User/announcement/announcement.js
"use strict";
const app = getApp()
Page({

  /**
   * Page initial data
   */
  data: {

  },
  copy(e) {
    wx.setClipboardData({
      data: e.currentTarget.dataset.copy
    })
  },
  /**
   * Lifecycle function--Called when page load
   */
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
  },
  officialStatus: function(e){
    console.log(e);
  }
})