<template>
    <view>

        <view>
            <swiper class="swiper" :indicator-dots="building.img.length == 1 ? false : true" indicator-active-color="#fff"
             autoplay="1" interval="3000" duration="500">
                <block v-for="(item,index) in building.img" :key="index">
                    <swiper-item>
                        <image class="swiper-image" :src="item"> </image>
                    </swiper-item>
                </block>
            </swiper>
            <view class="building">
                <view class="building-name">{{building.name}}</view>
                <navigator class="nav-map" :url="'polyline?latitude='+building.latitude+'&longitude='+building.longitude">
                    <image src="/static/camptour/location.svg"> </image>
                </navigator>
            </view>

            <view class="descript">
                <view class='description'>
                    <rich-text :nodes="building.description"></rich-text>
                </view>
            </view>
        </view>

    </view>
</template>

<script>
    export default {
        data: () => ({
            tid: 0,
            bid: 0,
            building: {
                img: [] //加载中图片地址
            },
        }),
        onLoad: function(options) {
            var bid = ~~(options.bid);
            var tid = ~~(options.tid);
            if (!options.bid || !options.tid) {
                var data = uni.$app.data.introduce;
            } else {
                var data = uni.$app.data.tmp.map[tid].data[bid];
            }
            this.bid = bid
            this.tid = tid
            this.building = data
            uni.setNavigationBarTitle({title: data.name})
        },
        methods: {

        }
    }
</script>

<style>
    page {
        padding: 0;
    }

    .description {
        padding: 40rpx 40rpx;
        line-height: 30px;
        font-size: 30rpx;
    }

    .swiper {
        height: 40vh;
    }

    .swiper-image {
        width: 100%;
        height: 100%;
    }

    .building {
        height: 10vh;
        display: flex;
        margin: auto 20rpx;
    }

    .building-name {
        margin: auto 15rpx;
        width: 80%;
        color: #079df2;
        font-size: 50rpx;
        white-space: nowrap;
    }

    .nav-map {
        margin: auto 10rpx;
    }

    .nav-map image {
        width: 80rpx;
        height: 80rpx;
    }

    .descript {
        background: #f8f8f8;
        width: 100%;
        min-height: 49vh;
    }

    .descript rich-text {
        font-size: 30rpx;
        color: #000;
    }
</style>
