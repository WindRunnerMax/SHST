var app = getApp();
var amapFile = require('../../../utils/amap-wx.js');
Page({
  data: {
    latitude: null,
    longitude: null,
    markers: [],
    distance: '',
    polyline: []
  },
  onLoad: function (options) {
    if (!app.globalData.islocation){
      wx.showModal({
        title: '提示',
        content: '本功能需要您的位置信息，请检查是否给予微信以及小程序定位权限，点击确定进入小程序授权页设置',
        success(res) {
          if (res.confirm) {
            wx.openSetting({
              success: function (data) {
                if (data.authSetting["scope.userLocation"] === true) {
                  wx.getLocation({
                    type: 'wgs84',
                    success: function (res) {
                      app.globalData.latitude = res.latitude;
                      app.globalData.longitude = res.longitude;
                      app.globalData.islocation = true;
                    }
                  })
                }
              }
            });

          } 
        },
        complete:()=>{
          wx.navigateBack();
        }
      })
      return false;
    }
    wx.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#079DF2',
      animation: {
        duration: 200,
        timingFunc: 'easeIn'
      }
    })
    var _this = this;
    wx.getLocation({
      type: 'gcj02',
      success: function (res) {
        _this.setData({
          latitude: res.latitude,
          longitude: res.longitude
        });
        _this.routing(options);
      }
    })
  },
  routing: function (options){
    var _this = this;
    let distance = Math.abs(_this.data.longitude - options.longitude) + Math.abs(_this.data.latitude - options.latitude)
    console.log(distance);
    var myAmapFun = new amapFile.AMapWX({ key: require('../../../vector/camptour/config.js').key });
    let routeData = {
      origin: options.longitude + ',' + options.latitude,
      destination: _this.data.longitude + ',' + _this.data.latitude,
      success: function (data) {
        var points = [];
        if (data.paths && data.paths[0] && data.paths[0].steps) {
          var steps = data.paths[0].steps;
          for (var i = 0; i < steps.length; i++) {
            var poLen = steps[i].polyline.split(';');
            for (var j = 0; j < poLen.length; j++) {
              points.push({
                longitude: parseFloat(poLen[j].split(',')[0]),
                latitude: parseFloat(poLen[j].split(',')[1])
              })
            }
          }
        }
        _this.setData({
          markers: [{
            "width": "25",
            "height": "35",
            iconPath: "/vector/camptour/img/mapicon_end.png",
            latitude: options.latitude,
            longitude: options.longitude
          }, {
            "width": "25",
            "height": "35",
              iconPath: "/vector/camptour/img/mapicon_start.png",
            latitude: _this.data.latitude,
            longitude: _this.data.longitude
          }],
          polyline: [{
            points: points,
            color: "#0091ff",
            width: 6
          }]
        });
        if (data.paths[0] && data.paths[0].distance) {
          _this.setData({
            distance: data.paths[0].distance + '米'
          });
        }
      },
      fail: function (info) {
      }
    }
    if (distance < 0.85) {
      // getWalkingRoute 步行
      myAmapFun.getWalkingRoute(routeData)
    } else {
      // getDrivingRoute 驾车
      myAmapFun.getDrivingRoute(routeData)
    }
  }
})