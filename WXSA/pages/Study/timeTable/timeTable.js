//logs.js
"use strict";
const app = getApp()
const publicMethod = require('../../../vector/publicMethod.js');

Page({
  data: {
    next : '>',
    pre : '<',
    week: app.globalData.curWeek,
    ad: 1
  },
  onLoad(e){
    this.getCache(app.globalData.curWeek);
  },
  getCache: function(e){
    var that = this;
    var tableCache = wx.getStorageSync('table') || { classTable: [] };
    if (tableCache.term !== app.globalData.curTerm) {
      wx.setStorage({ key: "table", data: {term: app.globalData.curTerm, classTable: []}});
      this.getRemote(e);
    } else {
      if (tableCache.classTable && tableCache.classTable[e]) {
        console.log("GET TABLE FROM CACHE WEEK " + e);
        var tableCache = publicMethod.tableDispose(tableCache.classTable[e]);
        that.setData({
          table: tableCache,
          week: e
        })
      }else{
        this.getRemote(e);
      }
    }
  },
  getRemote: function (e) {
    var that = this;
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
        if (res.data.Message === "Yes") {
          console.log("GET TABLE FROM REMOTE WEEK " + e);
          var showTableArr = publicMethod.tableDispose(res.data.data);
          that.setData({
            table: showTableArr,
            week:res.data.week
          })
          var tableCache = wx.getStorageSync('table') || {term: app.globalData.curTerm, classTable: []};
          tableCache.classTable[e] = res.data.data;
          wx.setStorage({ key: 'table', data: tableCache })
        } else {
          app.toast("数据拉取失败");
        }
      }
    })
  },
  pre(e){
    if (e.target.dataset.week <= 1) return ;
    var week = parseInt(e.currentTarget.dataset.week) - 1;
    this.getCache(week);
  },
  next(e){
    var week = parseInt(e.currentTarget.dataset.week) + 1;
    this.getCache(week);
  },
  adError(e) {
    this.setData({
      ad: 0
    })
  },
  refresh(e){
    var week = parseInt(e.currentTarget.dataset.week);
    this.getRemote(week);    
  }
})
