"use strict";
const app = getApp()
const md5 = require('../utils/md5.js');

module.exports = {
  getNowFormatDate: getNowFormatDate,
  getCurWeek: getCurWeek,
  extDate: extDate
}

function extDate() {
  /**
   * 拓展Date
   */
  // console.log("拓展Date原型");
  Date.prototype.addDate = function(years = 0, months = 0, days = 0) {
    if (days !== 0) {
      this.setDate(this.getDate() + days);
    }
    if (months !== 0) {
      this.setMonth(this.getMonth() + months);
    }
    if (years !== 0) {
      this.setFullYear(this.getFullYear() + years);
    }
  }
}

function getNowFormatDate(yearAdd = 0, monthAdd = 0, dayAdd = 0) {
  var date = new Date();
  date.addDate(yearAdd, monthAdd, dayAdd);
  var year = date.getFullYear();
  var month = date.getMonth() + 1;
  var day = date.getDate();
  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;
  return year + "-" + month + "-" + day;
}

function dataDiff(startDateString, endDateString) {
  var separator = "-"; //日期分隔符
  var startDates = startDateString.split(separator);
  var endDates = endDateString.split(separator);
  var startDate = new Date(startDates[0], startDates[1] - 1, startDates[2]);
  var endDate = new Date(endDates[0], endDates[1] - 1, endDates[2]);
  var diff = parseInt((endDate - startDate) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
  return diff;
}

function getCurWeek(startTime) {
  if (getNowFormatDate() < startTime) return 0;
  var week = (parseInt(dataDiff(startTime, getNowFormatDate()) / 7) + 1);
  return week;
}