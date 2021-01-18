<template>
    <view>

        <layout title="嵙地图">
            <view style="position: relative;">
                <image src="http://wx4.sinaimg.cn/large/007U8ryUly1g5h4dl25bvj318g0u0b29.jpg" class="sdust-map" mode="widthFix"
                 @click="viewImg('http://wx4.sinaimg.cn/large/007U8ryUly1g5h4dl25bvj318g0u0b29.jpg')" ></image>
                <view class="img-from">山东科技大学新闻媒体部制</view>
            </view>
        </layout>

        <layout title="在线地图">
            <view class="tips">
                <view v-bind:style="{background: point}" class="a-dot"></view>
                <view class="info">{{info}}</view>
                <view class="loc">{{longitude.toFixed(6)}}</view>
                <view class="loc">{{latitude.toFixed(6)}}</view>
            </view>
            <map class="sdust-map" :longitude="longitude" :latitude="latitude" show-location></map>
        </layout>

    </view>
</template>

<script>
    export default {
        data: () => ({
            longitude: 120.12487,
            latitude: 35.99940,
            info: "定位中",
            point: "#FFB800",
        }),
        created: function() {
            uni.getLocation({
                type: "wgs84",
                // altitude: true, //高精度定位
                success: (res) => {
                    this.longitude = res.longitude;
                    this.latitude = res.latitude;
                    this.info = "定位成功";
                    this.point = "#009688";
                    console.log("location", res.longitude, res.latitude);
                },
                fail: () => {
                    this.info = "定位失败";
                    this.point = "#FF5722";
                }
            })
        },
        methods: {
            viewImg: function(url) {
                this.viewImage(url, [url]);
            }
        }
    }
</script>

<style scoped>
    .img-from {
        text-align: right;
        font-size: 12px;
        color: rgb(122, 122, 122);
        position: absolute;
        bottom: 7px;
        right: 5px;
    }

    .sdust-map {
        width: 100%;
        border-radius: 3px;
    }

    .tips{
      border:1px solid #eee;
      display: flex;
      flex-wrap: wrap;
      margin-bottom: 5px;
      background: #eee;
      color: #666;
      font-size: 15px;
    }

    .tips view{
      margin-left: 5px;
      align-self: center;
    }

    map{
        height: 230px;
    }
</style>
