//logs.js
"use strict";
const app = getApp()
const publicMethod = require('../../../vector/publicMethod.js');


Page({
  data: {
    next : '>',
    pre : '<',
    week: app.globalData.curWeek
  },
  onLoad: function (e) {
    var urlTemp = "";
    if (typeof (e) === "number") urlTemp += ("/" + e);
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + 'funct/sw/signalTable' + urlTemp,
      data: {
        week: app.globalData.curWeek,
        term: app.globalData.curTerm
      },
      fun: function (res) {
        if (res.data.Message === "Yes" && app.globalData.curWeek === parseInt(res.data.week)) {
          console.log("Storage");
          wx.setStorage({
            key: 'table',
            data: {
              week: app.globalData.curWeek,
              table: res.data.data
            }
          })
        }
        res.data.data = publicMethod.tableDispose(res.data.data);
        if (res.data.Message === "Yes") {
          that.setData({
            table: res.data.data,
            week:res.data.week
          })
        } else {
          app.toast("数据拉取失败");
        }
      }
    })
  },
  pre(e){
    if (e.target.dataset.week <= 1) return ;
    var week = parseInt(e.currentTarget.dataset.week) - 1;
    this.onLoad(week);
  },
  next(e){
    var week = parseInt(e.currentTarget.dataset.week) + 1;
    this.onLoad(week);
  }
})
