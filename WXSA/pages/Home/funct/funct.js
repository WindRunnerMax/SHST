// pages/funct/funct.js
"use strict";
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    adShow : 1
  },
  adError(e){
    this.setData({
      adShow : 0
    })
  },
  jump(e){
    if (e.currentTarget.dataset.checkuser === "0" && app.globalData.userFlag !== 1){
      if (app.globalData.userFlag === 0){
        wx.showModal({
          title: '提示',
          content: '该功能需要绑定强智教务系统，是否前去绑定',
          success: function (choice) {
            if (choice.confirm) {
              wx.navigateTo({
                url: '/pages/Home/Login/login?status=E'
              })
            }
          }
        })
      } else if (app.globalData.userFlag === 2){
        app.toast("数据加载中，请稍候");
      }
      return ;
    }

    wx.navigateTo({
      url: e.currentTarget.dataset.jumpurl
    })
  },
  viewImg(e){
      var current = e.currentTarget.dataset.viewimgurl;
      wx.previewImage({
        current: current,
        urls: [current]
      })
  }
})