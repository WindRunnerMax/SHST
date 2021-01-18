/**
 * 安全地使用Date对象 兼容IOS
 */
const safeDate = (...args) => {
    if(args[0] !== void 0 && typeof(args[0]) === "string") args[0] = args[0].replace(/\-/g,"/");
    return new Date(...args);
}

/**
 * yyyy年 MM月 dd日 hh1~12小时制(1-12) HH24小时制(0-23) mm分 ss秒 S毫秒 K周
 */
const formatDate = (fmt = "yyyy-MM-dd", date = safeDate()) => {
    var week = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];
    var o = {
        "M+": date.getMonth() + 1, //月份
        "d+": date.getDate(), //日
        "h+": date.getHours(), //小时
        "m+": date.getMinutes(), //分
        "s+": date.getSeconds(), //秒
        "q+": Math.floor((date.getMonth() + 3) / 3), //季度
        "S": date.getMilliseconds(), //毫秒
        "K": week[date.getDay()]
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (date.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (( "00" + o[k]).substr(("" + o[k]).length)));
    }
    return fmt;
}

/**
 * 增加时间
 */
const addDate = (date, years = 0, months = 0, days = 0) => {
    if (days !== 0) date.setDate(date.getDate() + days);
    if (months !== 0) date.setMonth(date.getMonth() + months);
    if (years !== 0) date.setFullYear(date.getFullYear() + years);
    return date;
}

/**
 * 拓展Date原型 增加时间
 */
const extDate = () => {
    Date.prototype.addDate = function(years = 0, months = 0, days = 0) {
        if (days !== 0) this.setDate(this.getDate() + days);
        if (months !== 0) this.setMonth(this.getMonth() + months);
        if (years !== 0) this.setFullYear(this.getFullYear() + years);
    }
}

/**
 * 日期相差天数
 */
const dayDiff = (startDateString, endDateString) => {
    var separator = "-"; //日期分隔符
    var startDates = startDateString.split(separator);
    var endDates = endDateString.split(separator);
    var startDate = safeDate(startDates[0], startDates[1] - 1, startDates[2]);
    var endDate = safeDate(endDates[0], endDates[1] - 1, endDates[2]);
    var diff = parseInt((endDate - startDate) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
    return diff;
}

/**
 * 精确的时间差
 */
const timeDiff = (startDateString, endDateString) => {
    var oldDate = safeDate(startDateString);
    var newDate = safeDate(endDateString);
    var difftime = (newDate - oldDate) / 1000; // 转为秒
    var days = parseInt(difftime / 86400);
    var hours = parseInt(difftime / 3600) - 24 * days;
    var minutes = parseInt(difftime % 3600/60);
    var seconds = parseInt(difftime % 60);
    return {days, hours, minutes, seconds};
}

export { safeDate, formatDate, extDate, dayDiff, addDate, timeDiff }
export default { safeDate, formatDate, extDate, dayDiff, addDate, timeDiff }
