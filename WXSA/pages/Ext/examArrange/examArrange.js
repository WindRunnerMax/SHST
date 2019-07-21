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
          app.toast("数据拉取失败");
        }
      }
    });
  }
})