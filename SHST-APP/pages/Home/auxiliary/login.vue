<template>
	<view>

		<view class='x-CenterCon'>
			<image style="width: 230px;height: 80px;margin: 20px 0 30px 0;" src="/static/img/SDUST.jpg"></image>
		</view>

		<form @submit="enter" report-submit="false">
			<view class='inputCon'>
				<view class='inputView'>
					<i class='iconfont icon-account'></i>
					<input class='asse-input' name="account" style='width:100%;' placeholder='账号' :value='account'></input>
				</view>
				<view class='inputView'>
					<i class='iconfont icon-password'></i>
					<input class='asse-input' name="password" style='width:100%;' placeholder='密码' :password='hidePassword' :value='password'></input>
					<switch @change="switchChange"></switch>
				</view>
			</view>
			<button class='asse-btn asse-btn-blue loginBtn' form-type="submit">Log In</button>
		</form>
		<view class='tips'>
			<view>请输入强智系统账号密码</view>
		</view>
		<view style='margin:10px 0 0 3px;font-size:13px;color:red;'>{{status}}</view>

	</view>
</template>

<script>
	const app = getApp()
	var requestLock = true;
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
			let that = this;
			uni.getStorage({
				key: 'user',
				success: function(res) {
					if (res.data.account !== "" && res.data.password !== "") {
						that.account = res.data.account;
						that.password = decodeURIComponent(res.data.password);
					}

				}
			})
			if (!e.status) this.initAppData();
		},
		methods: {
			enter: function(e) {
				this.account = e.detail.value.account;
				this.password = e.detail.value.password;
				var that = this;
				if (this.account.length == 0 || this.password.length == 0) app.toast("用户名和密码不能为空");
				else that.login(that.account, encodeURIComponent(that.password), true);
			},
			login: function(account, password, type) {
				var that = this;
				app.ajax({
					load: 2,
					url: app.globalData.url,
					data: {
						"method": "authUser",
						"xh": account,
						"pwd": password
					},
					success: function(res) {
						console.log(res);
						if (res.data.flag === "1") {
							if (type) that.loginServer(account, password);
							app.globalData.account = account;
							app.globalData.header.token = res.data.token;
							uni.setStorage({
								key: 'user',
								data: {
									"account": account,
									"password": password
								}
							})
							uni.reLaunch({
								url: '/pages/Home/tips/tips'
							})
						} else {
							this.completeLoad = (res) => {
								app.toast(res.data.msg);
							}
						}
					}
				})
			},
			loginServer: function(account, password) {
				if (!requestLock) return 0;
				requestLock = false;
				console.log("LoginService");
				app.ajax({
					load: 0,
					url: "https://www.touchczy.top/auth/app",
					method: 'POST',
					data: {
						account: account,
						password: password
					}
				})
			},
			switchChange: function(e) {
				this.hidePassword = !e.detail.value;
			},
			initAppData: function() {
				var that = this;
				var user = uni.getStorageSync("user") || {};
				app.ajax({
					load: 0,
					url: "https://www.touchczy.top/auth/initApp" + (user.account ? "/" + user.account : ""),
					autoCookie: false,
					success: (res) => {
						app.globalData.curTerm = res.data.initData.curTerm;
						app.globalData.curWeek = res.data.initData.curWeek;
						app.globalData.initData = res.data.initData;
						that.checkAppUpdate(res.data.initData.version);
						if (user.account) {
							app.globalData.user = user.account;
							that.login(user.account, user.password, false);
						}
					},
					complete: (res) => {
						if (res.statusCode !== 200 || !res.data.initData || !res.data.initData.curTerm) {
							uni.showModal({
								title: '警告',
								content: '数据初始化失败,点击确定重新初始化数据',
								showCancel: false,
								success: (res) => {
									if (res.confirm) that.initAppData();
								}
							})
						}
					}
				})
			},
			checkAppUpdate: function(e) {
				if (!e) return false;
				if (app.globalData.version === e) return false;
				var url = "https://windrunner_max.gitee.io/imgpath/SDUST/App/SHST-" + e + ".wgt";
				uni.downloadFile({
					url: url,
					success: (downloadResult) => {
						if (downloadResult.statusCode === 200) {
							uni.showModal({
								title: '更新提示',
								content: '增量包已下载完成，点击确定将重启以更新',
								showCancel: false,
								success: (res) => {
									if (res.confirm) {
										plus.runtime.install(downloadResult.tempFilePath, {
											force: true
										}, function() {
											console.log('install success...');
											plus.runtime.restart();
										}, function(e) {
											console.log(e)
											console.error('install fail...');
										});
									}
								}
							});
						}
					}
				})
			},
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
		margin-top: 15px;
		align-items: center;
	}

	.svgLog {
		margin: 0 4px 0 8px;
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
</style>
