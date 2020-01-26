<template>
	<view> 

		<view class="x-CenterCon">
			<imagecache imageStyle="width: 270px;height: 90px;margin-top: 20px;" src="https://windrunner_max.gitee.io/imgpath/SDUST/SHST.jpg">
			</imagecache>
		</view>


		<view class='userInfoCon'>
			<view class='unitInfo' style='border-top: 1px solid #eee;'>

				<view style='display:flex;'>
					<view>版本号</view>
				</view>
				<view>{{version}}</view>
			</view>

			<view class='unitInfo'>
				<view style='display:flex;'>
					<view>反馈QQ群</view>
				</view>
				<view class='link' data-copy='722942376' @tap='copy'>722942376</view>
			</view>

			<view class='unitInfo'>
				<view style='display:flex;'>
					<view>联系开发者</view>
				</view>
				<view class='link' data-copy='651525974' @tap='copy' style='display: flex;'>
					<view>651525974</view>
				</view>
			</view>

			<view class='unitInfo' data-jumpurl='https://github.com/WindrunnerMax/SW' @tap='jump'>
				<view style='display:flex;'>
					<view>项目开源地址</view>
				</view>
				<view >></view>
			</view>

			<view class='unitInfo' data-jumpurl="https://github.com/WindrunnerMax/SW/blob/SDUST/ChangeLog.md" @tap='jump'>
				<view style='display:flex;'>
					<view>项目更新日志</view>
				</view>
				<view>></view>
			</view>

		</view>



		<view class="footer">
			<view class="text">Copyright © 2019 WindrunnerMax</view>
		</view>

	</view>
</template>

<script>
	const app = getApp()
	export default {
		data() {
			return {
				version: app.globalData.version
			}
		},
		methods: {
			copy: function(e) {
				uni.setClipboardData({
					data: e.currentTarget.dataset.copy
				})
			},
			jump: function(e) {
				uni.navigateTo({
					url: '/pages/Home/auxiliary/webview?url=' + encodeURIComponent(e.currentTarget.dataset.jumpurl) 
				})
			}
		}
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

	.userInfoCon {
		margin-top: 27px;
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

	.github {
		overflow: hidden;
		max-width: 30%;
		text-overflow: ellipsis;
	}

	.footer {
		display: flex;
		margin-top: 30px;
		justify-content: center;
	}

	.footer .text {
		color: #aaa;
	}

	.Avatar {
		height: 20px;
		width: 20px;
		border-radius: 20px;
		align-self: center;
		margin-right: 3px;
	}
</style>
