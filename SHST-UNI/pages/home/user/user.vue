<template>
    <view>

        <layout>
            <view class="x-center">
                <image class="img" src="https://windrunner_max.gitee.io/imgpath/SHST/Static/SDUST.jpg"></image>
            </view>

            <view class="user-info-con">
                <view class="unit-info top">
                    <view>
                        <view>学号</view>
                    </view>
                    <view>{{account}}</view>
                </view>
                <view class="unit-info">
                    <view>
                        <view>姓名</view>
                    </view>
                    <view>{{name}}</view>
                </view>
                <view class="unit-info">
                    <view>
                        <view>学院</view>
                    </view>
                    <view>{{academy}}</view>
                </view>
                <!-- #ifdef MP-WEIXIN -->
                <view class="a-hide" :class="{'a-show':today > '2020-03-26'}">
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
                        <view>
                            <view>公告</view>
                        </view>
                        <view :style="{'background':'green', 'display':point}" class="point"></view>
                    </view>
                    <view class="iconfont icon-arrow-right"></view>
                </view>
                <view class="unit-info"  @click="jump('/pages/user/reward/reward')">
                    <view class="a-flex">
                        <view>
                            <view>赞赏</view>
                        </view>
                    </view>
                    <view class="iconfont icon-arrow-right"></view>
                </view>
                <view class="unit-info" @click="jump('/pages/user/about/about')">
                    <view>
                        <view>关于</view>
                    </view>
                    <view class="iconfont icon-arrow-right"></view>
                </view>

                <view class="a-btn a-btn-orange btn-full" @click="logout">注销</view>

            </view>
        </layout>

    </view>
</template>

<script>
    import util from "@/modules/datetime";
    export default {
        data: function() {
            return {
                academy: " ",
                name: " ",
                account: " ",
                point: "none",
                today: util.formatDate()
            }
        },
        created: function() {
            uni.$app.onload(async () => {
                uni.getStorage({
                    key: "point",
                    complete: (res) => {
                        if (res.data !== uni.$app.data.point) this.point = "block";
                    }
                })
                if (uni.$app.data.userFlag === 0) {
                    var tipsInfo = "游客";
                    this.academy = tipsInfo
                    this.name = tipsInfo
                    this.account = tipsInfo
                    return void 0;
                }
                var [err, res] = await uni.getStorage({key: "userInfo"});
                if (!err && res.data && res.data.account) {
                    console.log("GET USERINFO FROM CACHE");
                    this.academy = res.data.academy
                    this.name = res.data.name
                    this.account = res.data.account
                } else {
                    console.log("GET USERINFO FROM REMOTE");
                    var res = await uni.$app.request({
                        load: 1,                        
                        throttle: true,
                        url: uni.$app.data.url + "/sw/userInfo",
                    })
                    if (res.data.info) {
                        uni.setStorage({key: "userInfo", data: res.data.info})
                        this.academy = res.data.info.academy
                        this.name = res.data.info.name
                        this.account = res.data.info.account
                    } else {
                        uni.$app.toast("服务器错误");
                    }
                }
            })
        },
        methods: {
            copy: function(str) {
                uni.setClipboardData({data: str})
            },
            jump: function(url) {
                uni.navigateTo({url: url})
            },
            jumpUpdate: function(url) {
                this.point = "none";
                if (uni.hideTabBarRedDot) {
                    uni.hideTabBarRedDot({index: 2});
                }
                uni.navigateTo({url: url});
            },
            logout: function(e) {
                uni.navigateTo({url: "/pages/home/auxiliary/login?status=E"});
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
