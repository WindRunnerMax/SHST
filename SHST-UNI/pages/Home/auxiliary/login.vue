<template>
	<view>

		<view class='x-CenterCon'>
			<image style="width: 230px;height: 80px;margin: 20px 0 30px 0;" src="https://windrunner_max.gitee.io/imgpath/SHST/Static/SDUST.jpg"></image>
		</view>

		<form @submit="enter" report-submit="false">
			<view class='inputCon'>
				<view class='inputView'>
					<i class='iconfont icon-account'></i>
					<input class='a-input' name="account" style='width:100%;' placeholder='账号' :value='account'></input>
				</view>
				<view class='inputView'>
					<i class='iconfont icon-password'></i>
					<input class='a-input' name="password" style='width:100%;' placeholder='密码' :password='hidePassword' :value='password'></input>
					<switch @change="switchChange"></switch>
				</view>
			</view>
			<button class='a-btn a-btn-blue loginBtn' form-type="submit">Log In</button>
		</form>
		<view class='tips'>
			<view>请输入强智系统账号密码</view>
			<view style="color: #3CB371;" @click="exLogin">测试账号登陆</view>
		</view>
		<view style='margin:10px 0 0 3px;font-size:13px;color:red;'>{{status}}</view>

	</view>
</template>

<script>
	const app = getApp()
	export default {
		data() {
			return {
				account: "",
				password: "",
				status: "",
				hidePassword: true
			}
		},
		onLoad: function(e) {
			uni.getStorage({
				key: 'user',
				success: res => {
					if (res.data && res.data.account && res.data.password) {
						this.account = res.data.account
						this.password = decodeURIComponent(res.data.password)
					}

				}
			})
			uni.removeStorage({
				key: 'userInfo'
			})
			uni.removeStorage({
				key: 'table'
			})
			uni.removeStorage({
				key: 'event'
			})
			app.globalData.url = "https://www.touchczy.top/";
			app.globalData.userFlag = 0;
		},
		methods: {
			enter: async function(e) {
				this.account = e.detail.value.account;
				this.password = e.detail.value.password;
				if (this.account.length == 0 || this.password.length == 0) {
					app.toast("用户名和密码不能为空");
				} else {
					var res = await app.request({
						load: 3,
						// #ifdef MP-WEIXIN
						url: app.globalData.url + 'auth/login/1',
						// #endif
						// #ifdef MP-QQ
						url: app.globalData.url + 'auth/login/2',
						// #endif
						// #ifdef MP-ALIPAY
						url: app.globalData.url + 'auth/login/3',
						// #endif
						method: 'POST',
						data: {
							"account": this.account,
							"password": encodeURIComponent(this.password),
							"openid": app.globalData.openid
						},
					})
					console.log(res.data)
					if (res.data.Message == "No") {
						this.status = res.data.info
						app.toast(res.data.info);
					} else if (res.data.Message == "Yes") {
						uni.setStorage({
							key: 'user',
							data: {
								"account": this.account,
								"password": this.password,
							},
							success: function() {
								app.globalData.userFlag = 1;
								uni.reLaunch({
									url: '/pages/Home/tips/tips'
								})
							}
						})
					} else {
						this.status = "ERROR"
						app.toast("请求错误");
					}
				}
			},
			switchChange: function(e) {
				this.hidePassword = !e.detail.value
			},
			getUserInfo: function(e) {
				console.log(e)
				app.globalData.userInfo = e.detail.userInfo
				this.userInfo = e.detail.userInfo
				this.hasUserInfo = true
			},
			exLogin: function(e){
				app.globalData.url = "https://www.touchczy.top/example/";
				app.globalData.userFlag = 1;
				uni.reLaunch({
					url: '/pages/Home/tips/tips'
				})
			}
		}
	}
</script>

<style>
	page {
		background: #FFFFFF;
	}

	.inputCon {
		margin-top: 23px;
	}

	.inputView {
		display: flex;
		width: 100%;
		border-bottom: 1px solid #eee;
		margin-top: 5px;
		align-items: center;
	}

	.svgLog {
		margin: 0 0 0 8px;
		width: 20px;
		height: 20px;
		align-self: center;
	}

	.loginBtn {
		width: 100%;
		margin-top: 20px;
		border: none;
		box-sizing: border-box;
	}

	.tips {
		margin: 10px 0 0 3px;
		font-size: 13px;
		color: #79B2F9;
		display: flex;
		justify-content: space-between;
	}

	.wx-switch-input {
		zoom: .8;
	}

	.inputView i {
		color: #aaa;
		margin: 0 4px 0 8px;
		align-self: center;
	}

	.a-input {
		border: none;
		margin: 7px 0;
	}
</style>
