<template>
	<view>

		<layout>
			<view class='x-CenterCon'>
				<imagecache imageStyle="width: 230px;height: 80px;margin: 20px 0 30px 0;" src="https://windrunner_max.gitee.io/imgpath/SDUST/SDUST.jpg"></imagecache>
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

				<view class='unitInfo' data-jumpurl='/pages/Home/auxiliary/webview?url=https%3A%2F%2Fjq.qq.com%2F%3F_wv%3D1027%26k%3D5UPExxX' @tap='jump'>
					<view class='titleCon'>QQ群</view>
					<view>722942376</view>
				</view><strong></strong>
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
			console.log("GET USERINFO FROM REMOTE");
			app.ajax({
				load: 1,
				url: app.globalData.url,
				data: {
					"method": "getUserInfo",
					"xh": app.globalData.account
				},
				fun: function(res) {
					that.academy = res.data.yxmc
					that.name = res.data.xm
					that.account = app.globalData.account
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
					url: '/pages/Home/auxiliary/login?status=E'
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
