// pages/Ext/examArrange/examArrange.js
"use strict";
const app = getApp()
Page({

  /**
   * Page initial data
   */
  data: {
    tips:""
  },

  /**
   * Lifecycle function--Called when page load
   */
  onLoad: function (options) {
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + 'funct/sw/ExamArrange',
      fun: function (res) {
        console.log(res.data)
        if(res.data.MESSAGE === "Yes"){
          if(!res.data.data[0]) res.data.data = [];
          res.data.data.map((value) => {
            if(!value) return ;
            var gap = value.ksqssj.split("~");
            value.startTime = gap[0];
            value.endTime = gap[1];
            value.endTimeSplit = value.endTime.split(" ")[1];

            return value;
          })
          that.setData({
            exam: res.data.data.length !== 0 ? res.data.data : [],
            tips: res.data.data.length !== 0 ? "" : "暂无考试信息"
          })
        }else{
          app.toast("请求错误");
        }
      }
    });
  },

  /**
   * Lifecycle function--Called when page is initially rendered
   */
  onReady: function () {

  },

  /**
   * Lifecycle function--Called when page show
   */
  onShow: function () {

  },

  /**
   * Lifecycle function--Called when page hide
   */
  onHide: function () {

  },

  /**
   * Lifecycle function--Called when page unload
   */
  onUnload: function () {

  },

  /**
   * Page event handler function--Called when user drop down
   */
  onPullDownRefresh: function () {

  },

  /**
   * Called when page reach bottom
   */
  onReachBottom: function () {

  },

  /**
   * Called when user click on the top right corner to share
   */
  onShareAppMessage: function () {

  }
})