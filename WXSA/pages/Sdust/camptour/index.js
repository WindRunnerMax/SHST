//获取应用实例
var app = getApp();
let config = require('../../../vector/camptour/config.js')
Page({
  data: {
    fullscreen: false,
    latitude: 35.99940,
    longitude: 120.12487,
    buildlData: app.globalData.map,
    windowHeight: "",
    windowWidth: "",
    isSelectedBuild: 0,
    isSelectedBuildType: 0,
    imgCDN: app.imgCDN,
    islocation: true
  },
  loadNetWorkScoolConf: function () {
    var _this = this
    return new Promise(function (resolve, reject) {
      if (!_this.debug) {
        // 优先读取缓存信息
        var map = wx.getStorageSync('map')
        var introduce = wx.getStorageSync('introduce')
        if (map && introduce) {
          _this.globalData.map = map;
          _this.globalData.introduce = introduce;
        }

        // 再加载网络数据
        wx.request({
          url: config.updateUrl,
          header: {
            'content-type': 'application/json'
          },
          success: function (res) {
            console.log("加载远程数据")
            if (res.data.map && res.data.map.length > 0) {
              //刷新数据
              _this.globalData.map = res.data.map;
              _this.globalData.introduce = res.data.introduce;

              // 存储学校位置数据于缓存中
              wx.setStorage({
                key: "map",
                data: res.data.map
              })
              wx.setStorage({
                key: "introduce",
                data: res.data.introduce
              })
            }
          },
          complete: function (info) {
            resolve();
          }
        })
      } else {
        resolve();
      }
    });
  },

  loadSchoolConf: function () {
    app.globalData.map = this.school.map;
    this.loadNetWorkScoolConf().then(function () {
      // 渲染id
      for (let i = 0; i < app.globalData.map.length; i++) {
        for (let b = 0; b < app.globalData.map[i].data.length; b++) {
          app.globalData.map[i].data[b].id = b + 1;
        }
      }
    })

  },
  debug: config.debug, //开启后只调用本地数据
  imgCDN: config.imgCDN,
  school: require('../../../vector/camptour/resources/' + config.school),
  globalData: {
    userInfo: null,
    map: null,
    introduce: null,
    latitude: null,
    longitude: null
  },
  onLoad: function () {
    wx.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#079DF2',
      animation: {
        duration: 200,
        timingFunc: 'easeIn'
      }
    })

    var that = this;
    //载入学校位置数据
    this.loadSchoolConf()
    //如果已经授权，提前获取定位信息
    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标  
      success: function (res) {
        app.globalData.latitude = res.latitude;
        app.globalData.longitude = res.longitude;
        app.globalData.islocation = true
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
            console.log(res.windowWidth)
          }
        })
        //载入更新后的数据
        console.log(app.globalData.map)
        that.setData({
          buildlData: app.globalData.map
        })
      }, fail: () => {
        app.toast("您已禁止山科小站获取您的位置信息，无法使用此功能");
      }
    })


   
  },
  onShareAppMessage: function (res) {
    if (res.from === 'button') {
      // 来自页面内转发按钮
      console.log(res.target)
    }
    return {
      title: app.globalData.introduce.name + ' - 校园导览',
      path: '/pages/map/index',
      success: function (res) {
        // 转发成功
      },
      fail: function (res) {
        // 转发失败
      }
    }
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
  location: function () {
    var _this = this
    wx.getLocation({
      type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标  
      success: function (res) {
        app.globalData.latitude = res.latitude;
        app.globalData.longitude = res.longitude;
        _this.setData({
          longitude: res.longitude,
          latitude: res.latitude
        })
      }
    })
  },
  clickButton: function (e) {
    //console.log(this.data.fullscreen)
    //打印所有关于点击对象的信息
    this.setData({ fullscreen: !this.data.fullscreen })
  },
  changePage: function (event) {
    this.setData({
      isSelectedBuildType: event.currentTarget.id,
      isSelectedBuild: 0
    });
  }
})