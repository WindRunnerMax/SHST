"use strict";
const md5 = require('@/utils/md5.js');
const util = require('@/utils/util.js');


module.exports = {
	todoDateDiff: dataCalc,
	getCurWeek: getCurWeek,
	tableDispose: tableDispose
}

/**
 * 统一处理课表功能
 */
function tableDispose(info, flag = 0) {
	const app = getApp();
	const colorList = app.globalData.colorList;
	const colorN = app.globalData.colorList.length;
	var tableArr = [];
	var week = new Date().getDay() - 1;
	if (flag === 2) week++,flag--;
	if (week === -1) week = 6;
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

function dataCalc(startDateString, endDateString, content) {
	const app = getApp();
	const colorList = app.globalData.colorList;
	const colorN = app.globalData.colorList.length;
	var color = colorList[md5.hexMD5(content)[0].charCodeAt() % colorN];
	var diff = util.dateDiff(startDateString, endDateString);
	if (diff === 0) diff = "今";
	else if (diff < 0) diff = "超期" + Math.abs(diff);
	else diff = "距今" + Math.abs(diff);
	return [diff, color];
}


function getCurWeek(startTime) {
	console.log(util.formatDate())
	if (util.formatDate() < startTime) return 1;
	var week = (parseInt(util.dateDiff(startTime, util.formatDate()) / 7) + 1);
	return week;
}
