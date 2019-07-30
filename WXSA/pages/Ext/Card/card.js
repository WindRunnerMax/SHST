"use strict";
const app = getApp();
var cardLoad = true;

Page({

  data: {
    show: 0
  },

  onLoad: function (options) {
    var that = this;
    app.ajax({
      load:2,
      url: app.globalData.url + "funct/card/GetUserInfo",
      success: res => {
        if(res.data.Message === "Yes"){
          cardLoad = false;
          var info = res.data.info;
          var pregInfo = info.match(/<div align="left">[\S]*<\/div>/g);
          var balanceInfo = info.match(/<td class="neiwen">[\S]*<\/td>/g);
          var balance = balanceInfo[0].split("（")[0];
          var balanceTemp = balanceInfo[0].split("）")[1].split("(")[0];
          that.setData({
            name: pregInfo[0],
            account: pregInfo[3],
            banlance: balance,
            balanceTemp: balanceTemp
          })
        } else if (res.data.Message === "No"){
          app.toast(res.data.info);
        }else{
          app.toast("未知错误");
        }
      }
    })
  },
  todayQuery(){
    var that = this;
    if (cardLoad) {
      app.toast("请稍后");
      return ;
    }
    app.ajax({
      load: 2,
      url: app.globalData.url + "funct/card/TodayQuery",
      success: res => {
        if (res.data.Message === "Yes") {
          that.diposeCardData(res.data.info);          
        } else if (res.data.Message === "No") {
          app.toast(res.data.info);
        } else {
          app.toast("未知错误");
        }
      }
    })
  },
  historyQuery(){
    var that = this;    
    if (cardLoad) {
      app.toast("请稍后");
      return;
    }
    app.ajax({
      load: 2,
      url: app.globalData.url + "funct/card/HistoryQuery",
      success: res => {
        if (res.data.Message === "Yes") {
          that.diposeCardData(res.data.info);
        } else if (res.data.Message === "No") {
          app.toast(res.data.info);
        } else {
          app.toast("未知错误");
        }
      }
    })
  },
  diposeCardData(data){
    var line = [];
    var lineData = data.match(/<tr class="listbg[2]?">[\s\S]*?<\/tr>/g);
    if (!lineData){
      app.toast("暂无数据");
    } else{
      lineData.forEach((value) => {
        var infoArr = value.match(/<td[\s\S]*?>[\s\S]*?<\/td>/g);
        var infoObj = {};
        infoObj.time = infoArr[0].replace(/<[\S]?td[\s\S]*?>/g, "").substring(5,16);
        infoObj.status = infoArr[3].replace(/<[\S]?td[\s\S]*?>/g, "");
        infoObj.location = infoArr[4].replace(/<[\S]?td[\s\S]*?>/g, "");
        infoObj.money = infoArr[5].replace(/<[\S]?td[\s\S]*?>/g, "");
        infoObj.balance = infoArr[6].replace(/<[\S]?td[\s\S]*?>/g, "");
        infoObj.serno = infoArr[7].replace(/<[\S]?td[\s\S]*?>/g, "");
        line.push(infoObj);
      })
      console.log(line);
    }
    this.setData({
      data: line,
      show: 1
    })
  }
})