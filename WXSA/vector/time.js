const app = getApp()
const md5 = require('md5.js');
const colorList = app.globalData.colorList;
const colorN = colorList.length;

module.exports = {
  getNowFormatDate: getNowFormatDate,
  dateDiff: datacalc,
  getCurWeek: getCurWeek
}

function getNowFormatDate(id) {
  var date = new Date();
  var hour = date.getHours();
  var minutes = date.getMinutes();
  var year = date.getFullYear();
  var month = date.getMonth() + 1;
  var day = date.getDate();
  if (id == 2) year++;
  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;
  return year + "-" + month + "-" + day;
}

function datacalc(startDateString, endDateString, content) {
  var color = colorList[md5.hexMD5(content)[0].charCodeAt() % colorN];
  console.log(colorN);
  var diff = dataDiff(startDateString, endDateString);
  if (diff === 0) diff = "今";
  else if (diff < 0) diff = "超期" + Math.abs(diff);
  else diff = "距今" + Math.abs(diff);
  return [diff, color];
}

function dataDiff(startDateString, endDateString){
  var separator = "-"; //日期分隔符
  var startDates = startDateString.split(separator);
  var endDates = endDateString.split(separator);
  var startDate = new Date(startDates[0], startDates[1] - 1, startDates[2]);
  var endDate = new Date(endDates[0], endDates[1] - 1, endDates[2]);
  var diff = parseInt((endDate - startDate) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
  return diff;
}

function getCurWeek(startTime){
  return (parseInt(dataDiff(startTime, getNowFormatDate(1))/7) + 1);
}