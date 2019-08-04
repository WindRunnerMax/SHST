// pages/about/about.js
"use strict";
const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    version: app.globalData.version
  },
  copy(e) {
    wx.setClipboardData({
      data: e.currentTarget.dataset.copy
    })
  },
  jump(e) {
    wx.navigateTo({
      url: e.currentTarget.dataset.jumpurl
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    /*wx.setNavigationBarColor({
      frontColor: "#000000",
      backgroundColor: '#CEECF4'
    });*/
  },
  onShareAppMessage(options) {
    var that = this;
    var shareObj = {　　　　
      title: "山科小站",
      path: '/pages/Home/Tips/tips'
    }
    return shareObj;
  }
})