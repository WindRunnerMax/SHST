// pages/event/event.js
"use strict";
const app = getApp()
const md5 = require('../../../utils/md5.js');
const time = require('../../../vector/time.js');
const dispose = require('../../../vector/dispose.js');
const publicMethod = require('../../../vector/publicMethod.js');
var tableLoadFlag = true;
var eventLoadFlag = true;
var bindSWLoadFlag = true;
var articalLoadFlag = true;

Page({

  /**
   * 页面的初始数据
   */
  data: {
    todayWeather: ["", "CLEAR_DAY", 0, 0, "数据获取中"],
    tomorrowWeather: ["", "CLEAR_DAY", 0, 0],
    tdatomoWeather: ["", "CLEAR_DAY", 0, 0],
    tips: "数据加载中",
    todoList: [],
    tips2: "数据加载中",
    artical : "数据加载中",
    host: app.globalData.host
  },
  onLoad: function(options) {
    this.getWeather();
    this.getCacheTable();
    this.getCacheEvent();
    app.eventBus.on('LoginEvent', this.openidEvent);
    if (app.globalData.openid !== ""){
      this.loginSatatus(app.globalData.loginStatus);
      this.getArtical();
      this.getRemoteTable();
      this.getRemoteEvent();
    }
  },
  openidEvent: function(data) {
    console.log("Login EventBus Execute");
    this.loginSatatus(data.data.Message);
    this.getArtical();
    this.getRemoteTable();
    this.getRemoteEvent();
  },
  loginSatatus: function(status){
    if (!bindSWLoadFlag) return "DONE";
    bindSWLoadFlag = false;
    if (status === "Yes") {
      this.setData({
        tips: "点我前去绑定教务系统账号"
      })
    }
  },
  getArtical(){
    if (articalLoadFlag){
      articalLoadFlag = false;
      if (app.globalData.initData.articalName)
      this.setData({
        artical: app.globalData.initData.articalName
      })
    }
  },
  /**
   * 课表处理
   */
  getCacheTable() {
    var that = this;
    tableLoadFlag = true;
    var cacheTable = wx.getStorageSync('table') || "";
    if (cacheTable !== "") {
      if (app.globalData.curWeek === cacheTable.week) {
        console.log("GET TABLE FROM CACHE");
        tableLoadFlag = false;
        cacheTable.table = publicMethod.tableDispose(cacheTable.table, 1);
        that.setData({
          table: cacheTable.table ? cacheTable.table : [],
          tips: cacheTable.table ? "" : "No Class Today"
        })
      }
    }
  },
  getRemoteTable() {
    var that = this;
    if (app.globalData.userFlag === 1 && tableLoadFlag) {
      console.log("GET TABLE FROM REMOTE");
      app.ajax({
        load: 1,
        url: app.globalData.url + 'funct/sw/signalTable',
        data: {
          week: app.globalData.curWeek,
          term: app.globalData.curTerm
        },
        fun: function (res) {
          if (res.data.Message === "Yes") {
            wx.setStorage({
              key: 'table',
              data: {
                week: app.globalData.curWeek,
                table: res.data.data
              }
            })
          }
          res.data.data = publicMethod.tableDispose(res.data.data, 1);
          if (res.data.Message === "Yes") {
            that.setData({
              table: res.data.data ? res.data.data : [],
              tips: res.data.data ? "" : "No Class Today"
            })
          } else {
            app.toast("ERROR");
            that.setData({
              tips: "加载失败"
            })
          }
        }
      })
    }
  },

  /**
   * 天气处理
   */
  getWeather() {
    var that = this;
    var ran = parseInt(Math.random() * 100000000000);
    app.ajax({
      autoCookie: false,
      url: "https://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=" + ran,
      fun: function(res) {
        if (res.data.status === "ok") {
          var weatherData = res.data.result.daily;
          that.setData({
            todayWeather: [weatherData.skycon[0].date, weatherData.skycon[0].value, weatherData.temperature[0].min, weatherData.temperature[0].max, res.data.result.hourly.description],
            tomorrowWeather: [weatherData.skycon[1].date, weatherData.skycon[1].value, weatherData.temperature[1].min, weatherData.temperature[1].max],
            tdatomoWeather: [weatherData.skycon[2].date, weatherData.skycon[2].value, weatherData.temperature[2].min, weatherData.temperature[2].max]
          })
        } else {
          app.toast("WEATHER ERROR");
        }
      }
    })
  },

  /**
   * 事件处理
   */
  eventDipose(data) {
    var that = this;
    wx.setStorageSync('event', data);
    eventLoadFlag = false;
    if (data.length === 0) {
      that.setData({
        tips2: "暂没有待办事项"
      })
      return;
    } else {
      that.setData({
        tips2: ""
      })
    }
    var curData = time.getNowFormatDate();
    data.map(function(value) {
      var diff_color = time.dateDiff(curData, value.todo_time, value.event_content);
      value.diff = diff_color[0];
      value.color = diff_color[1];
      return value;
    })
    data.sort((a, b) => {
      return a.todo_time > b.todo_time ? 1 : -1;
    });
    that.setData({
      todoList: data
    })
  },
  getCacheEvent() {
    var that = this;
    var eventCache = wx.getStorageSync('event') || "";
    eventLoadFlag = true;
    if (eventCache !== "") {
      console.log("GET EVENT FROM CACHE");
      that.eventDipose(eventCache);
    }
  },
  getRemoteEvent() {
    var that = this;
    if (!eventLoadFlag) return false;
    if (app.globalData.openid === "") {
      that.setData({
        tips2: "未正常获取用户信息"
      })
      return false;
    }
    console.log("GET EVENT FROM REMOTE");
    app.ajax({
      url: app.globalData.url + "funct/todo/getevent",
      fun: res => {
        if (res.data.data && res.data.data != 3) {
          that.eventDipose(res.data.data);
        } else {
          that.setData({
            tips2: "加载失败"
          })
        }
      }
    })
  },
  setStatus(e) {
    var that = this;
    wx.showModal({
      title: '提示',
      content: '确定标记为已完成吗',
      success: function(choice) {
        if (choice.confirm) {
          var index = e.currentTarget.dataset.index;
          var id = e.currentTarget.dataset.id;
          app.ajax({
            url: app.globalData.url + "funct/todo/setStatus",
            method: "POST",
            data: {
              id: id
            },
            fun: res => {
              app.toast("标记成功");
              that.data.todoList.splice(index, 1);
              wx.setStorageSync('event', that.data.todoList);
              that.setData({
                todoList: that.data.todoList,
                tips2: that.data.todoList.length === 0 ? "暂没有待办事项" : "",
              })
            }
          })
        }
      }
    })
  },
  bindSW() {
    if (app.globalData.userFlag === 0) {
      wx.navigateTo({
        url: '/pages/Home/Login/login?status=E'
      })
    } else return 0;
  },
  articalJump(){
    if (app.globalData.initData.articalUrl){
      var url = encodeURIComponent(app.globalData.initData.articalUrl);
      wx.navigateTo({
        url: '/pages/Home/Webview/webview?url=' + url
      })
    }
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function() {

  }
})