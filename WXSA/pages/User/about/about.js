// pages/about/about.js
"use strict";
const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    version:app.globalData.version
  },
  copy(e){
    wx.setClipboardData({
      data: e.currentTarget.dataset.copy
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    /*wx.setNavigationBarColor({
      frontColor: "#000000",
      backgroundColor: '#CEECF4'
    });*/
  }
})