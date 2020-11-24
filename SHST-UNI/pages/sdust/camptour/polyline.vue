<template>
    <view>

        <map id="navi-map" :longitude="longitude" :latitude="latitude" scale="14" :markers="markers" :polyline="polyline"
         :include-points="markers" class="navi-map" show-location enable-overlooking="true" enable-3D="true">

            <cover-view class="distance">{{distance}}</cover-view>
        </map>

    </view>
</template>

<script>
    import amapFile from "@/utils/amap-wx";
    import config from "@/vector/resources/camptour/config";
    export default {
        data: () => ({
            latitude: null,
            longitude: null,
            markers: [],
            distance: '',
            polyline: []
        }),
        onLoad: function(options) {
            if (!uni.$app.data.islocation) {
                uni.showModal({
                    title: '提示',
                    content: '本功能需要您的位置信息，请检查是否给予微信以及小程序定位权限，点击确定进入小程序授权页设置',
                    success: function(res) {
                        if (res.confirm) {
                            uni.openSetting({
                                success: function(data) {
                                    if (data.authSetting["scope.userLocation"] === true) {
                                        uni.getLocation({
                                            type: 'wgs84',
                                            success: function(res) {
                                                uni.$app.data.latitude = res.latitude;
                                                uni.$app.data.longitude = res.longitude;
                                                uni.$app.data.islocation = true;
                                            }
                                        })
                                    }
                                }
                            });

                        }
                    },
                    complete: () => {
                        uni.navigateBack();
                    }
                })
                return false;
            }
            uni.getLocation({
                type: 'gcj02',
                success: (res) => {
                    this.latitude = res.latitude;
                    this.longitude = res.longitude;
                    this.routing(options);;
                }
            })
        },
        methods: {
            routing: function(options) {
                let distance = Math.abs(this.longitude - options.longitude) + Math.abs(this.latitude - options.latitude)
                console.log(distance);
                var myAmapFun = new amapFile.AMapWX({
                    key: config.key
                });
                let routeData = {
                    origin: options.longitude + ',' + options.latitude,
                    destination: this.longitude + ',' + this.latitude,
                    success: (data) => {
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
                        this.markers = [{
                            "width": "25",
                            "height": "35",
                            iconPath: "/static/camptour/mapicon_end.png",
                            latitude: options.latitude,
                            longitude: options.longitude
                        }, {
                            "width": "25",
                            "height": "35",
                            iconPath: "/static/camptour/mapicon_start.png",
                            latitude: this.latitude,
                            longitude: this.longitude
                        }];
                        this.polyline = [{
                            points: points,
                            color: "#0091ff",
                            width: 6
                        }];
                        if (data.paths[0] && data.paths[0].distance) {
                            this.distance = data.paths[0].distance + '米'
                        }
                    },
                    fail: function(info) {}
                }
                if (distance < 0.85) {
                    // getWalkingRoute 步行
                    myAmapFun.getWalkingRoute(routeData)
                } else {
                    // getDrivingRoute 驾车
                    myAmapFun.getDrivingRoute(routeData)
                }
            }
        }
    }
</script>

<style>
    page {
        padding: 0;
    }

    .navi-map {
        width: auto;
        height: 100vh;
    }

    .distance {
        position: absolute;
        bottom: 30px;
        right: 10px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        padding: 3px 5px 3px 5px;
        color: #fff;
        background: #0091ff;
        border-radius: 5px;
    }
</style>
