<template>
    <view>

        <view class="x-center">
            <image class="img" src="http://dev.shst.touchczy.top/public/static/img/SHST.jpg">
            </image>
        </view>


        <view class="user-info-con">
            <view class="unit-info top">
                <view class="a-flex">
                    <view>版本号</view>
                </view>
                <view>{{version}}</view>
            </view>
            <view class="a-hide" :class="{'a-show':today > '2020-09-01'}">
                <view class="unit-info">
                    <view class="a-flex">
                        <view>反馈QQ群</view>
                    </view>
                    <view class="a-link"  @click="copy('722942376')">722942376</view>
                </view>
            </view>

            <view class="unit-info">
                <view class="a-flex">
                    <view>联系开发者</view>
                </view>
                <view class="a-link" @click="copy('651525974')">
                    <view>651525974</view>
                </view>
            </view>

            <view class="unit-info">
                <view class="a-flex">
                    <view>项目开源地址</view>
                </view>
                <view data-copy="" @click="copy('https://github.com/WindrunnerMax/SHST')" class="a-link">点我复制链接</view>
            </view>

            <view class="unit-info">
                <view class="a-flex">
                    <view>项目更新日志</view>
                </view>
                <view @click="copy('https://github.com/WindrunnerMax/SHST/blob/dev/ChangeLog.md')" class="a-link">点我复制链接</view>
            </view>

            <button class="unit-info" open-type="share" style="font-size: 14px;">
                <view class="a-flex">
                    <view>分享山科小站</view>
                </view>
                <view class="iconfont icon-arrow-right"></view>
            </button>
        </view>

        <view class="footer">
            <view class="text">Copyright © 2020 WindrunnerMax</view>
        </view>

    </view>
</template>

<script>
    import {formatDate} from "@/modules/datetime";
    export default {
        data: () => ({
            version: uni.$app.data.version,
            today: formatDate()
        }),
        onShareAppMessage: () => {
            var shareObj = {
                title: "山科小站",
                path: "/pages/home/tips/tips"
            }
            return shareObj;
        },
        methods: {}
    }
</script>

<style>
    page {
        padding: 0;
        background: #FFFFFF;
        font-size: 14px;
    }

    .img {
        width: 270px;
        height: 90px;
        margin-top: 20px;
    }

    .user-info-con {
        margin-top: 27px;
        padding: 10px;
    }
    
    .top{
        border-top: 1px solid #eee;
    }

    .unit-info {
        height: 30px;
        line-height: 30px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        padding: 10px 15px;
    }

    .footer {
        display: flex;
        margin-top: 30px;
        justify-content: center;
    }

    .footer .text {
        color: #aaa;
    }

</style>
