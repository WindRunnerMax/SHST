<template>
	<view>

		<layout>
			<view class='x-CenterCon'>
				<image style="width: 230px;height: 80px;margin: 20px 0 30px 0;" src="https://windrunner_max.gitee.io/imgpath/SDUST/SDUST.jpg"></image>
			</view>

			<view class='userInfoCon'>
				<view class='unitInfo' style=' border-top: 1px solid #eee; '>
					<view class='titleCon'>
						<view>学号</view>
					</view>
					<view>{{account}}</view>
				</view>
				<view class='unitInfo'>
					<view class='titleCon'>
						<view>姓名</view>
					</view>
					<view>{{name}}</view>
				</view>
				<view class='unitInfo'>
					<view class='titleCon'>
						<view>学院</view>
					</view>
					<view>{{academy}}</view>
				</view>
				<!-- #ifdef MP-WEIXIN -->
				<view class='unitInfo' data-copy='722942376' @tap='copy'>
					<view class='titleCon'>QQ群</view>
					<view>722942376</view>
				</view><strong></strong>
				<!-- #endif -->
				<!-- #ifdef MP-QQ -->
				<button open-type="openGroupProfile" class='unitInfo' group-id="722942376" >
					<view class='titleCon'>QQ群</view>
					<view>722942376</view>
				</button>
				<!-- #endif -->
				<view class='unitInfo' data-jumpurl="/pages/User/announce/announce" @tap='jumpUpdate'>
					<view style='display:flex;'>
						<view class='titleCon'>
							<view>公告</view>
						</view>
						<view :style="{'background':'green','display':point}" class='point'></view>
					</view>
					<view>></view>
				</view>
				<view class='unitInfo' data-jumpurl="/pages/User/reward/reward" @tap='jump'>
					<view style='display:flex;'>
						<view class='titleCon'>
							<view>赞赏</view>
						</view>
					</view>
					<view>></view>
				</view>
				<view class='unitInfo' data-jumpurl="/pages/User/about/about" @tap='jump'>
					<view class='titleCon'>
						<view>关于</view>
					</view>
					<view>></view>
				</view>

				<view class="asse-btn asse-btn-orange" style="width: 100%;margin: 18px 0 0px 0;box-sizing: border-box;" @tap='logout'>注销</view>

			</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp()
	export default {
		data() {
			return {
				academy: " ",
				name: " ",
				account: " ",
				point: "none"
			}
		},
		onLoad: function() {
			var that = this;
			uni.getStorage({
				key: 'point',
				complete: (res) => {
					if (res.data !== app.globalData.tips) that.point = "block";
				}
			})
			if (app.globalData.userFlag === 0) {
				var tipsInfo = "游客";
				that.academy = tipsInfo
				that.name = tipsInfo
				that.account = tipsInfo
				return;
			}
			uni.getStorage({
				key: 'userInfo',
				success: res => {
					console.log("GET USERINFO FROM CACHE");
					that.academy = res.data.academy
					that.name = res.data.name
					that.account = res.data.account
				},
				fail: res => {
					console.log("GET USERINFO FROM REMOTE");
					app.ajax({
						load: 1,
						url: app.globalData.url + 'sw/userInfo',
						fun: res => {
							if (res.data.info) {
								uni.setStorage({
									key: 'userInfo',
									data: res.data.info
								})
								that.academy = res.data.info.academy
								that.name = res.data.info.name
								that.account = res.data.info.account
							} else {
								app.toast("服务器错误");
							}

						}
					})
				}
			})
		},
		methods: {
			copy(e) {
				uni.setClipboardData({
					data: e.currentTarget.dataset.copy
				})
			},
			jump(e) {
				uni.navigateTo({
					url: e.currentTarget.dataset.jumpurl
				})
			},
			jumpUpdate(e) {
				this.point = "none";
				if (uni.hideTabBarRedDot) {
					uni.hideTabBarRedDot({
						index: 2
					})
				}
				uni.navigateTo({
					url: e.currentTarget.dataset.jumpurl
				})
			},
			logout(e) {
				uni.navigateTo({
					url: '/pages/Home/auxiliary/login?status = E'
				})
			}
		}
	}
</script>

<style>
	.userInfoCon {
		padding: 10px;
	}

	.unitInfo {
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
</style>
