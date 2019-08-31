//获取应用实例
var app = getApp();
let schoolConfig = require('../../../vector/camptour/resources/sdust');
Page({
  school: schoolConfig,
  data: {
    fullscreen: false,
    latitude: 35.99940,
    longitude: 120.12487,
    buildlData: {},
    windowHeight: "",
    windowWidth: "",
    isSelectedBuild: 0,
    isSelectedBuildType: 0,
    islocation: true
  },
  loadSchoolConf: function () {
    app.globalData.map = this.school.map;
    for (let i = 0; i < app.globalData.map.length; i++) {
      for (let b = 0; b < app.globalData.map[i].data.length; b++) {
        app.globalData.map[i].data[b].id = b + 1;
      }
    }
  },
  onLoad: function () {
    var that = this;
    wx.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#079DF2',
      animation: {
        duration: 200,
        timingFunc: 'easeIn'
      }
    })
    wx.showShareMenu({
      withShareTicket: true
    })
    wx.getSystemInfo({
      success: function (res) {
        //获取当前设备宽度与高度，用于定位控键的位置
        that.setData({
          windowHeight: res.windowHeight,
          windowWidth: res.windowWidth,
        })
      }
    })
    this.loadSchoolConf();
    that.setData({
      buildlData: app.globalData.map
    })
    this.location();
  },
  regionchange(e) {
    // 视野变化
    // console.log(e.type)
  },
  markertap(e) {
    // 选中 其对应的框
    this.setData({
      isSelectedBuild: e.markerId
    })
    // console.log("e.markerId", e.markerId)
  },
  navigateSearch() {
    wx.navigateTo({
      url: 'search'
    })
  },
  location: function (e) {
    var _this = this
    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标  
      success: function (res) {
        app.globalData.latitude = res.latitude;
        app.globalData.longitude = res.longitude;
        app.globalData.islocation = true;
        if(e){
          _this.setData({
            longitude: res.longitude,
            latitude: res.latitude
          })
        }
      }
    })
  },
  clickButton: function (e) {
    this.setData({ fullscreen: !this.data.fullscreen })
  },
  changePage: function (event) {
    this.setData({
      isSelectedBuildType: event.currentTarget.id,
      isSelectedBuild: 0
    });
  },
  onShareAppMessage: function () {

  }
})