<template>
    <view>

        <view class="x-center">
            <image class="img" src="https://windrunner_max.gitee.io/imgpath/SHST/Static/SDUST.jpg"></image>
        </view>

        <form @submit="enter">
            <view class="input-con">
                <view class="input-view y-center x-full">
                    <i class="iconfont icon-account"></i>
                    <input class="a-input x-full" name="account" placeholder="账号" :value="account" type="number"></input>
                </view>
                <view class="input-view y-center x-full a-lmt">
                    <i class="iconfont icon-password"></i>
                    <input class="a-input x-full" name="password" placeholder="密码" :password="hidePassword" :value="password"></input>
                    <switch @change="switchChange"></switch>
                </view>
            </view>
            <button class="a-btn a-btn-blue login-btn x-full" form-type="submit">Log In</button>
        </form>
        <view class="tips a-flex-space-between">
            <view>请输入强智系统账号密码</view>
            <view style="color: #3CB371;" @click="exLogin">测试账号登陆</view>
        </view>
        <view  class="status">{{status}}</view>

    </view>
</template>

<script>
    export default {
        data: () => ({
                account: "",
                password: "",
                status: "",
                hidePassword: true
        }),
        created: function() {
            uni.$app.onload(() => {
                uni.getStorage({
                    key: "user",
                    success: res => {
                        if (res.data && res.data.account && res.data.password) {
                            this.account = res.data.account;
                            this.password = res.data.password;
                        }
                    }
                })
                uni.removeStorage({key: "userInfo"})
                uni.removeStorage({key: "table"})
                uni.removeStorage({key: "event"})
                uni.$app.data.url = uni.$app.data.url.replace("/example/", "");
                uni.$app.data.userFlag = 0;
            })
        },
        methods: {
            enter: async function(e) {
                this.account = e.detail.value.account;
                this.password = e.detail.value.password;
                if (this.account.length == 0 || this.password.length == 0) {
                    uni.$app.toast("用户名和密码不能为空");
                } else {
                    var res = await uni.$app.request({
                        load: 3,
                        // #ifdef MP-WEIXIN
                        url: uni.$app.data.url + "/auth/login/1",
                        // #endif
                        // #ifdef MP-QQ
                        url: uni.$app.data.url + "/auth/login/2",
                        // #endif
                        method: "POST",
                        throttle: true,
                        data: {
                            "account": this.account,
                            "password": encodeURIComponent(this.password),
                            "openid": uni.$app.data.openid
                        },
                    })
                    console.log(res.data)
                    if (res.data.status === 2) {
                        this.status = res.data.msg
                        uni.$app.toast(res.data.msg);
                    } else if (res.data.status === 1) {
                        uni.setStorage({
                            key: "user",
                            data: {
                                "account": this.account,
                                "password": this.password,
                            },
                            success: function() {
                                uni.$app.data.userFlag = 1;
                                uni.reLaunch({url: "/pages/home/tips/tips"})
                            }
                        })
                    } else {
                        this.status = "ERROR";
                        uni.$app.toast("请求错误");
                    }
                }
            },
            switchChange: function(e) {
                this.hidePassword = !e.detail.value;
            },
            exLogin: function(e){
                uni.$app.data.url = uni.$app.data.url + "/example/";
                uni.$app.data.userFlag = 1;
                uni.reLaunch({url: "/pages/home/tips/tips"})
            }
        }
    }
</script>

<style>
    page {
        background: #FFFFFF;
    }
    
    .img{
        width: 230px;
        height: 80px;
        margin: 20px 0 30px 0;
    }
    
    .x-full{
        width: 100%;
    }

    .input-con {
        margin-top: 23px;
    }

    .input-view {
        border-bottom: 1px solid #eee;
        margin-top: 5px;
    }

    .login-btn {
        margin-top: 20px;
        border: none;
        box-sizing: border-box;
    }

    .tips {
        margin: 10px 0 0 3px;
        font-size: 13px;
        color: #79B2F9;
    }

    .input-view i {
        color: #aaa;
        margin: 0 4px 0 8px;
        align-self: center;
    }

    .a-input {
        border: none;
        margin: 7px 0;
    }
    
    .status{
        color:red;
        font-size: 13px;
        margin-left: 3px;
    }
</style>
