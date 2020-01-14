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
				<view class='icon' data-jumpurl="/pages/Study/tableShare/tableShare" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-fly'></i>
					<view>共享课表</view>
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
				<!-- #ifdef MP-WEIXIN -->
				<navigator class='icon' target="miniProgram" app-id="wxa53da62a53aaddea" hover-class="none" version="release">
					<i class='iconfont icon-lubiao-xf '></i>
					<view>迎新专版</view>
				</navigator>
				<!-- #endif -->

				<!-- #ifdef MP-QQ -->
				<navigator class='icon' target="miniProgram" app-id="1110074303" hover-class="none" version="release">
					<i class='iconfont icon-lubiao-xf '></i>
					<view>迎新专版</view>
				</navigator>
				<!-- #endif -->

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
				<view class='icon' data-jumpurl="/pages/Sdust/camptour/index" data-checkuser="1" @tap='jump'>
					<i class='iconfont icon-nav'></i>
					<view>校园导览</view>
				</view>
			</view>
		</layout>

		<layout title="拓展" color="#6495ED">
			<view class="y-CenterCon" style="color: #6495ED;">
				<view class='icon' data-jumpurl="/pages/Ext/link/link" data-checkuser="1" @tap='jump'>
					<i class='iconfont icon-link'></i>
					<view>分享链接</view>
				</view>
				 <!-- #ifndef MP-WEIXIN -->
				<view class='icon' data-jumpurl="/pages/Ext/event/event" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-schedule'></i>
					<view>待办管理</view>
				</view>
				<!-- #endif -->
				<view class='icon' data-jumpurl="/pages/Ext/examArrange/examArrange" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-exam'></i>
					<view>考试安排</view>
				</view>
				<view class='icon' data-jumpurl="/pages/Ext/card/card" data-checkuser="0" @tap='jump'>
					<i class='iconfont icon-xuehao'></i>
					<view>校园卡</view>
				</view>
				<!-- #ifdef MP-WEIXIN -->
				<button open-type='feedback' class='icon' style="color: inherit;" hover-class="none">
					<i class='iconfont icon-fankui'></i>
					<view>意见反馈</view>
				</button>
				<!-- #endif -->
			</view>
		</layout>

		<layout v-if="adShow">
			<!-- #ifdef MP-WEIXIN -->
			<ad unit-id="adunit-b82100ae7bddf4ad" @error="adError" class="adapt"></ad>
			<!-- #endif -->
			<!-- #ifdef MP-QQ -->
			<ad unit-id="001b7e7e765436c6351d8a6d693437d2" @error="adError" class="adapt"></ad>
			<!-- #endif -->
		</layout>

	</view>
</template>

<script>
	const app = getApp();
	const util = require("@/utils/util.js")
	export default {
		data() {
			return {
				adShow: 1,
				now: util.formatDate()
			}
		},
		methods: {
			adError(e) {
				this.adShow = 0
			},
			jump(e) {
				if (e.currentTarget.dataset.checkuser === "0" && app.globalData.userFlag !== 1) {
					if (app.globalData.userFlag === 0) {
						uni.showModal({
							title: '提示',
							content: '该功能需要绑定强智教务系统，是否前去绑定',
							success: function(choice) {
								if (choice.confirm) {
									uni.navigateTo({
										url: '/pages/Home/auxiliary/login?status=E'
									})
								}
							}
						})
					} else if (app.globalData.userFlag === 2) {
						console.log(2)
						app.toast("数据加载中，请稍候");
					}
					return false;
				}
				console.log(e)
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
