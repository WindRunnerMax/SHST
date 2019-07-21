// pages/classroom/classroom.js
"use strict";
const app = getApp();
const time = require('../../../vector/time.js');
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show: 0,
    searchData: time.getNowFormatDate(),
    searchTime: '0102',
    searchFloor: 1,
    index: [0, 0, 0]
  },
  flagChange(e) {
    var flagIndex = parseInt(e.currentTarget.dataset.index);
    this.data.flag[flagIndex] = this.data.flag[flagIndex] === 'none' ? "flex" : "none";
    this.setData({
      flag: this.data.flag
    })
  },
  loadClassroom(e) {
    var that = this;
    wx.setNavigationBarTitle({ title: '加载中...' })
    setTimeout(() => that.loadClassroomSetTime(e),300);
  },
  loadClassroomSetTime(e) {
    var that = this;
    app.ajax({
      load: 2,
      data: {
        searchData: that.data.searchData,
        searchTime: that.data.searchTime,
        searchFloor: that.data.searchFloor,
      },
      url: app.globalData.url + 'funct/sw/classroomExt2',
      fun: res => {
        if (res.data.MESSAGE !== "Yes") {
          app.toast("ERROR");
          return;
        }
        var data = res.data.data;
        console.log(data)
        data[0].jsList.sort((a, b) => {
          return a.jsmc > b.jsmc ? 1 : -1;
        });
        that.setData({
          room: data,
          show: 1,
          qShow: that.data.queryTime[that.data.index[1]][2],
          searchData: that.data.searchData
          // searchFloor: that.data.searchFloor[that.data.index[2]][0],
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {
    var queryData = this.getTimeArr();
    var queryTime = [
      ['12节', '0102', '12节(8:00-9:50)'],
      ['34节', '0304', '34节(10:10-12:00)'],
      ['56节', '0506', '56节(14:00-15:50)'],
      ['78节', '0708', '78节(16:00-17:50)'],
      ['9X节', '0910', '9X节(19:00-20:50)'],
      ['上午', 'am', '上午(8:00-12:00)'],
      ['下午', 'pm', '下午(14:00-17:50)'],
      ['全天', 'allday', '全天(8:00-20:50)']
    ];
    var queryFloor = [
      ["J1", "1"],
      ["J3", "3"],
      ["J5", "5"],
      ["J7", "7"],
      ["J14", "14"],
      ["S1", "S1"]
    ];
    this.setData({
      queryData: queryData,
      queryTime: queryTime,
      queryFloor: queryFloor
    })
  },
  getTimeArr() {
    var weekShow = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
    var date = new Date();
    var year = date.getFullYear();
    var queryDataArr = [];
    var week = new Date().getDay();
    console.log(week);
    for (var i = 0; i < 7; ++i) {
      let monthTemp = date.getMonth() + 1;;
      let dayTemp = date.getDate();
      let weekTemp = week + i;
      if (monthTemp < 10) monthTemp = "0" + monthTemp;
      if (dayTemp < 10) dayTemp = "0" + dayTemp;
      queryDataArr.push([year + "-" + monthTemp + "-" + dayTemp, weekShow[weekTemp % 7]]);
      date.addDate(0, 0, 1);
    }
    console.log(queryDataArr);
    return queryDataArr;
  },
  bindPickerChange(e) {
    var that = this;
    this.data.index = e.detail.value;
    this.data.searchData = that.data.queryData[e.detail.value[0]][0];
    this.data.searchTime = that.data.queryTime[e.detail.value[1]][1]
    this.data.searchFloor = that.data.queryFloor[e.detail.value[2]][1]
  },
  resetInfo() {
    this.setData({
      searchData: time.getNowFormatDate(),
      searchTime: '0102',
    })
  }
})