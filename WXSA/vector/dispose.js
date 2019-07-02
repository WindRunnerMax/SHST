"use strict";
const md5 = require('md5.js');
var app = getApp();
module.exports = {
  tableDispose: tableDispose
}

/**
 * 统一处理课表功能
 */
function tableDispose(info, flag = 0) {
  var tableArr = [];
  const week = new Date().getDay() - 1;
  info.forEach(value => {
    if (!value) return;
    var arrInner = [];
    var day = parseInt(value.kcsj[0]) - 1;
    if (flag === 1 && day !== week) return;
    var knot = parseInt(parseInt(value.kcsj.substr(1, 2)) / 2);
    var md5Str = md5.hexMD5(value.kcmc);
    var colorSignal = app.globalData.colorList[Math.abs((md5Str[0].charCodeAt() - md5Str[3].charCodeAt())) % app.globalData.colorN];
    arrInner.push(day);
    arrInner.push(knot);
    arrInner.push(value.kcmc.split("（")[0]);
    arrInner.push(value.jsxm);
    arrInner.push(value.jsmc);
    arrInner.push(colorSignal);
    if (!tableArr[day]) tableArr[day] = [];
    tableArr[day][knot] = arrInner;
  })
  if (flag === 1) return tableArr[week];
  else return tableArr;
}