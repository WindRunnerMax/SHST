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
      data: app.globalData.version
    })
  }
})