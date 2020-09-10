"use strict";
import util from "@/modules/datetime";
import md5 from "@/utils/md5.js";

/**
 * 统一处理课表功能
 */
function tableDispose(info, oneDay = false) {
    const app = getApp();
    var tableArr = [];
    var week = new Date().getDay() - 1;
    if (week === -1) week = 6;
    info.forEach(value => {
        if (!value) return void 0;
        var classObj = {};
        var day = ~~(value.kcsj[0]) - 1;
        if (oneDay && day !== week) return void 0;
        var knot = ~~(~~(value.kcsj.substr(1, 2)) / 2);
        var uniqueNum = Array.prototype.reduce.call(value.kcmc, (pre, cur) => pre+cur.charCodeAt(), 0);
        var colorSignal = app.data.colorList[ uniqueNum % app.data.colorN];
        classObj.day = day;
        classObj.knot = knot;
        classObj.className = value.kcmc.split("（")[0];
        classObj.teacher = value.jsxm;
        classObj.classroom = value.jsmc;
        classObj.background = colorSignal;
        if(!tableArr[day]) tableArr[day] = [];
        if(!tableArr[day][knot]) tableArr[day][knot] = {background: colorSignal, table: []};
        tableArr[day][knot].table.push(classObj);
    })
    if (oneDay) return tableArr[week];
    else return tableArr;
}

function todoDateDiff(startDateString, endDateString, content) {
    const app = getApp();
    const colorList = app.data.colorList;
    const colorN = app.data.colorList.length;
    var color = colorList[content.charCodeAt() % colorN];
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

export {todoDateDiff, getCurWeek, tableDispose}

export default {todoDateDiff, getCurWeek, tableDispose}
