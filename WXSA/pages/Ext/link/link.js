// pages/link/link.js
"use strict";
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {

  }, copy(e) {
    wx.setClipboardData({
      data: e.currentTarget.dataset.copy
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + "funct/ext/urlshare",
      fun: res => {
        that.setData({
          data: res.data.url
        })
      }
    })
  }
})