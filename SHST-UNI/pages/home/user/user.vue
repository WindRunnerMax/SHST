<template>
    <view>

        <layout>
            <view class="x-center">
                <image class="img" src="http://dev.shst.touchczy.top/public/static/img/SDUST.jpg"></image>
            </view>

            <view class="user-info-con">
                <view class="unit-info top">
                    <view>学号</view>
                    <view>{{account}}</view>
                </view>
                <view class="unit-info">
                    <view>姓名</view>
                    <view>{{name}}</view>
                </view>
                <view class="unit-info">
                    <view>学院</view>
                    <view>{{academy}}</view>
                </view>
                <!-- #ifdef MP-WEIXIN -->
                <view class="a-hide" :class="{'a-show':today > '2020-09-01'}">
                    <view class="unit-info" @click="copy('722942376')">
                        <view>QQ群</view>
                        <view>722942376</view>
                    </view>
                </view>
                <!-- #endif -->
                <!-- #ifdef MP-QQ -->
                <button open-type="openGroupProfile" class="unit-info" group-id="722942376">
                    <view>QQ群</view>
                    <view>722942376</view>
                </button>
                <!-- #endif -->
                <view class="unit-info" @click="jumpUpdate('/pages/user/announce/announce')">
                    <view class="a-flex">
                        <view>公告</view>
                        <view :style="{'background':'green', 'display':point}" class="point"></view>
                    </view>
                    <view class="iconfont icon-arrow-right"></view>
                </view>
                <!-- #ifdef MP-WEIXIN -->
                <view class="unit-info"  @click="nav('/pages/user/reward/reward')">
                    <view class="a-flex">赞赏</view>
                    <view class="iconfont icon-arrow-right"></view>
                </view>
                <!-- #endif -->
                <!-- #ifdef MP-QQ -->
                <view class="unit-info"  @click="nav('/pages/user/reward/reward-list')">
                    <view class="a-flex">
                        <view>赞赏</view>
                    </view>
                    <view class="iconfont icon-arrow-right"></view>
                </view>
                <!-- #endif -->
                <view class="unit-info" @click="nav('/pages/user/about/about')">
                    <view>关于</view>
                    <view class="iconfont icon-arrow-right"></view>
                </view>
                <view class="a-btn a-btn-orange btn-full" @click="logout">注销</view>

            </view>
        </layout>

    </view>
</template>

<script>
    import util from "@/modules/datetime";
    import storage from "@/modules/storage.js";
    export default {
        data: () => ({
            academy: " ",
            name: " ",
            account: " ",
            point: "none",
            today: util.formatDate()
        }),
        created: function() {
            uni.$app.onload(async () => {
                storage.getPromise("point").then(res => {
                    if (res !== uni.$app.data.point) this.point = "block";
                })
                if (uni.$app.data.userFlag === 0) {
                    let tipsInfo = "游客";
                    this.academy = tipsInfo;
                    this.name = tipsInfo;
                    this.account = tipsInfo;
                    return void 0;
                }
                var res = await storage.getPromise("user-info");
                if (res && res.account) {
                    console.log("GET USERINFO FROM CACHE");
                    this.academy = res.academy;
                    this.name = res.name;
                    this.account = res.account;
                } else {
                    console.log("GET USERINFO FROM REMOTE");
                    var res = await uni.$app.request({
                        load: 1,
                        throttle: true,
                        url: uni.$app.data.url + "/sw/userInfo",
                    })
                    if (res.data.info) {
                        storage.setPromise("user-info", res.data.info);
                        this.academy = res.data.info.academy;
                        this.name = res.data.info.name;
                        this.account = res.data.info.account;
                    } else {
                        uni.$app.toast("服务器错误");
                    }
                }
            })
        },
        methods: {
            jumpUpdate: function(url) {
                this.point = "none";
                if(uni.hideTabBarRedDot) uni.hideTabBarRedDot({index: 2});
                this.nav(url);
            },
            logout: function(e) {
                this.nav("/pages/home/auxiliary/login?status=E");
            }
        }
    }
</script>

<style scoped>
    .img{
        width: 230px;
        height: 80px;
        margin: 20px 0 30px 0;
    }
    .user-info-con {
        padding: 10px;
    }

    .user-info-con > .top{
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

    .point {
        width: 8px;
        height: 8px;
        border-radius: 8px;
        align-self: center;
        margin-left: 8px;
    }

    .btn-full{
        width: 100%;
        margin: 18px 0 0px 0;
        box-sizing: border-box;
    }
</style>
