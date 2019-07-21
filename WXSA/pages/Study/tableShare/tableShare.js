// pages/link/link.js
"use strict";
const dispose = require('../../../vector/dispose.js');
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    account: "",
    name: ""
  },
  accountInput(e) {
    this.setData({
      account: e.detail.value
    })
  },
  nameInput(e) {
    this.setData({
      name: e.detail.value
    })
  },
  req() {
    var that = this;
    if (this.data.account === "" | this.data.name === "") {
      app.toast("请输入完整信息");
    }
    app.ajax({
      url: app.globalData.url + "funct/share/startreq",
      method: 'POST',
      data: {
        account: this.data.account,
        user: this.data.name
      },
      fun: res => {
        app.toast(res.data.message);
        that.onLoad();
      }
    })
  },
  cancelreq(){
    var that = this;
    app.ajax({
      url: app.globalData.url + "funct/share/cancelreq",
      fun: res => {
        app.toast(res.data.message);
        that.onLoad();
      }
    })
  },
  agree(e) {
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + "funct/share/agreereq",
      data: {
        id: e.currentTarget.dataset.id
      },
      fun: res => {
        app.toast(res.data.message);
        that.onLoad();
      }
    })
  },
  lifting(e){
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + "funct/share/lifting",
      data: {
        id: e.currentTarget.dataset.id
      },
      fun: res => {
        app.toast("成功");
        that.onLoad();
      }
    })
  },
  refuse(e) {
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + "funct/share/refusereq",
      data: {
        id: e.currentTarget.dataset.id
      },
      fun: res => {
        app.toast(res.data.message);
        that.onLoad();
      }
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var that = this;
    app.ajax({
      load: 2,
      url: app.globalData.url + "funct/share/signalTableshare2",
      data: {
        week: app.globalData.curWeek,
        term: app.globalData.curTerm
      },
      fun: res => {
        if (res.data.info.succ){
          res.data.info.succ.timetable1 = dispose.tableDispose(res.data.info.succ.timetable1);
          res.data.info.succ.timetable2 = dispose.tableDispose(res.data.info.succ.timetable2);
        }
        console.log(res.data.info);
        that.setData({
          data: res.data.info
        })
      }
    })
  }
})