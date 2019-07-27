"use strict";
const app = getApp();
const time = require('../../../vector/time.js');
function getFormateDateAcceptDate(date){
  var year = date.getFullYear();
  var month = date.getMonth() + 1;
  var day = date.getDate();
  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;
  return year + "-" + month + "-" + day;
}

Page({

  /**
   * 页面的初始数据
   */
  data: {
    term: app.globalData.curTerm
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var weekCount = 24;
    // var startDate = app.globalData.curTermStart;
    var startDate = "2019-08-26";
    var vacationWeekStart = 19;
    var startDateObj = new Date(startDate);
    var curDateObj = time.getNowFormatDate();
    console.log(startDateObj.getDay());
    var calendarObj = [];
    var monthFlag = 0;
    for(var i = 1 ; i <= weekCount ; ++i){
      var lineCalen=[];
      lineCalen.push({"className":"week","day":i.toString()})
      for(var k = 1;k <= 7 ; ++k){
        var unitCalen = {className:"m1"};
        var calcMonth = (startDateObj.getMonth() + 1);
        if(calcMonth % 2 === 0) unitCalen.className="m2";
        if (calcMonth !== monthFlag){
          unitCalen.day = calcMonth + "月";
          unitCalen.className += " strong";
          monthFlag = calcMonth;
        } else unitCalen.day = startDateObj.getDate().toString();
        if (k === 6 || k === 7 || i >= vacationWeekStart) unitCalen.className += " vacation";
        if (curDateObj === getFormateDateAcceptDate(startDateObj)) unitCalen.className += " today";
        lineCalen.push(unitCalen);
        startDateObj.addDate(0,0,1);
      }
      calendarObj.push(lineCalen);
    }
    this.setData({
      calendarObj: calendarObj
    })
    console.log(calendarObj);
  }
})