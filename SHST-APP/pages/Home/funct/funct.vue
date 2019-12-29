<template>
	<view> 

		<layout title="学习" color="#FF6347">
			<view class="y-CenterCon" style="color: #FF6347;">
				<view class='icon' data-jumpurl="/pages/Study/timeTable/timeTable" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-kebiao'></i>
					<view>查课表</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Study/classroom/classroom" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-classroom'></i>
					<view>查教室</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Study/grade/grade" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-grade'></i>
					<view>查成绩</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Ext/examArrange/examArrange" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-exam'></i>
					<view>考试安排</view>
				</view>

			</view>
		</layout>

		<layout title="信息" color="#3CB371">
			<view class="y-CenterCon" style="color: #3CB371;">
				<view class='icon' data-jumpurl="/pages/Lib/library/search" data-checkuser="1" @tap='jump'>
					<i class='iconfont icon-lib'></i>
					<view>图书检索</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Lib/borrow/borrow" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-borrow'></i>
					<view>借阅查询</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Ext/link/link" data-checkuser="1" @tap='jump'>
					<i class='iconfont icon-link'></i>
					<view>分享链接</view>
				</view>
			</view>
		</layout>

		<layout title="科大" color="#9F8BEC">
			<view class="y-CenterCon" style="color: #9F8BEC;">
				<view class='icon' data-jumpurl="/pages/Sdust/map/map" data-checkuser="1" @tap='jump'>
					<i class='iconfont icon-map'></i>
					<view>嵙地图</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Sdust/calendar/calendar" data-checkuser="1" @tap='jump'>
					<i class='iconfont icon-calendar'></i>
					<view>校历</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Sdust/vacation/vacation" data-checkuser="1" @tap='jump'>
					<i class='iconfont icon-vacation'></i>
					<view>放假安排</view>
				</view>
			</view>
		</layout>

		<layout title="广告">
			<view :data-jumpurl="adUrl" @tap='jump' style="margin-top: 8px;">
				<image mode="widthFix" style="width: 100%;" :src="adImg"></image>
			</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp();
	export default {
		data() {
			return {
				adImg: "",
				adUrl: ""
			}
		},
		onLoad: function() {
			this.adImg = app.globalData.initData.appAdImg;
			this.adUrl = '/pages/Home/auxiliary/webview?url=' + encodeURIComponent(app.globalData.initData.appAdUrl);
		},
		methods: {
			adError(e) {
				this.setData({
					adShow: 0
				})
			},
			jump(e) {
				uni.navigateTo({
					url: e.currentTarget.dataset.jumpurl
				})
			}
		}
	}
</script>

<style>
	.icon {
		width: 100%;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		padding: 5px 0;
	}

	.icon view {
		color: #000000;
	}

	.iconfont {
		font-size: 27px;
		color: inherit !important;
		margin: 10px 0;
	}
</style>
