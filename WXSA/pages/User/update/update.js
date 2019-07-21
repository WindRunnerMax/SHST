// pages/update/update.js
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
  }
})