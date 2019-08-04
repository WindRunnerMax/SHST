"use strict";
const app = getApp()
let config = require('../../../vector/camptour/config.js');
Page({

  /**
   * Page initial data
   */
  data: {
    host: config.cdnHost
  },
  viewImg(e) {
    var current = e.currentTarget.dataset.viewimgurl;
    wx.previewImage({
      current: current,
      urls: [current]
    })
  },
  onShareAppMessage: function () {

  }
})