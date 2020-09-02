<template>
	<view>

		<layout>
			<view class="centerCon" style="margin-top: 20px;">
				<image class='img' src="https://windrunner_max.gitee.io/imgpath/SHST/Static/SHST.jpg">
				</image>
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

				<view class='unitInfo'>
					<view style='display:flex;'>
						<view>项目开源地址</view>
					</view>
					<view data-copy='https://github.com/WindrunnerMax/SW' @tap='copy' class='link github'>点我复制链接</view>
				</view>


				<button class='unitInfo' open-type='share'>
					<view style='display:flex;'>
						<view>分享山科小站</view>
					</view>
					<view>></view>
				</button>
			

			</view>

		</layout>


	</view>
</template>

<script>
	const app = getApp()
	import layout from "@/components/layout.vue"
	export default {
		components: {
			layout
		},
		data() {
			return {
				version: app.globalData.version
			}
		},
		methods: {
			copy(e) {
				wx.setClipboardData({
					data: e.currentTarget.dataset.copy
				})
			}
		}
	}
</script>

<style>
	.centerCon {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.img {
		width: 320px;
		height: 109px;
	}

	.userInfoCon {
		margin-top: 20px;
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

	button:after {
		border: none;
	}

	button {
		background: #fff;
		border: none;
		font-size: 14px;
		box-sizing: unset;
	}

	button:hover {
		opacity: 1;
		background: #fff;
		color: #000;
	}
</style>
