<template>
	<view>

		<layout>
			<weather></weather>
		</layout>

		<view>
			<headslot :title="today">
				<view class="y-CenterCon">
					<view class='iconfont icon-shuaxin icon refresh' @tap='refresh'></view>
				</view>
			</headslot>
			<layout>
				<view class='articalCon' @tap='articalJump' style="margin-top: -7px;">
					<i class='iconfont icon-gonggao icon'></i>
					<rich-text class='link'>{{artical}}</rich-text>
				</view>
				<navigator url="/pages/User/announce/announce" open-type="navigate" class='articalCon' hover-class="none">
					<i class='iconfont icon-gonggao icon'></i>
					<rich-text class='link'>更多公告...</rich-text>
				</navigator>
			</layout>
		</view>


		<layout :title="hours > 21 ? '明日课程' : '今日课程'">
			<view v-for="(item,index) in table" :key="index">
				<view class='unitTable' v-show="item">
					<view class="y-CenterCon" style="margin: 5px 0;">
						<view class="dot" :style="{'background':item[5],'margin':'0 6px 0 3px'}"></view>
						<view>第{{2*(item[1] + 1) - 1}}{{2*(item[1] + 1)}}节</view>
						<view style="margin-left: 5px;">{{item[3]}}</view>
					</view>
					<view class="y-CenterCon">
						<view style="margin: 3px;">{{item[2]}}</view>
						<view style="margin-left: 5px;">{{item[4]}}</view>
					</view>
				</view>
			</view>
			<view class='unitTable' v-if="tips">
				<view class="y-CenterCon" style="margin: 5px 0;">
					<view class="dot" style='background:#eee;margin: 0 6px 0 3px;'></view>
					<view>{{tips}}</view>
				</view>
				<view style="margin:7px 3px 5px 3px;">{{tipsInfo}}</view>
			</view>
		</layout>

		<layout title="每日一句">
			<sentence></sentence>
		</layout>

	</view>
</template>

<script>
	import weather from "@/components/weather.vue"
	import sentence from "@/components/sentence.vue"
	import headslot from "@/components/headslot.vue"
	const app = getApp()
	const util = require("@/utils/util.js")
	const pubFct = require("@/vector/pubFct.js")

	export default {
		components: {
			weather,
			sentence,
			headslot
		},
		data() {
			return {
				today: util.formatDate("yyyy-MM-dd K"),
				hours: util.formatDate("h"),
				table: [],
				todoList: [],
				tips: "数据加载中",
				tipsInfo: "数据加载中",
				artical: "数据加载中",
			}
		},
		onLoad: function(options) {
			this.getArtical();
			this.getRemoteTable();
		},
		methods: {
			/**
			 * 课表处理
			 */
			getRemoteTable: function(load = 1) {
				var that = this;
				console.log("GET TABLE FROM REMOTE");
				app.ajax({
					load: load,
					url: app.globalData.url,
					data: {
						"method": "getKbcxAzc",
						"xnxqid": app.globalData.curTerm,
						"zc": app.globalData.curWeek,
						"xh": app.globalData.account
					},
					fun: function(res) {
						try {
							var showTableArr = pubFct.tableDispose(res.data, app.hours > 21 ? 1 : 2);
							that.tipsDispose(showTableArr);
						} catch (e) {
							app.toast("ERROR");
							that.tips = "加载失败";
							that.tipsInfo = "加载失败了，重新登录试一下";
						}
					}
				})
			},
			tipsDispose: function(info) {
				this.table = info ? info : [];
				this.tips = info ? "" : ( this.hours > 21 ? "NO Class Tomorrow" : "No Class Today");
				this.tipsInfo = info ? "" : ( this.hours > 21 ? "明天没课呢，晚上可以好好休息一下了" : "今天没有课，快去自习室学习吧");
			},
			refresh: function(info) {
				this.getRemoteTable(2);
			},
			getArtical: function() {
				if (app.globalData.initData && app.globalData.initData.articalName) {
					this.artical = app.globalData.initData.articalName
				}
			},
			articalJump: function() {
				if (app.globalData.initData && app.globalData.initData.articleUrl) {
					var url = encodeURIComponent(app.globalData.initData.articleUrl);
					uni.navigateTo({
						url: '/pages/Home/auxiliary/webview?url=' + url
					})
				}
			}
		}
	}
</script>

<style>
	.articalCon {
		display: flex;
		align-items: center;
		padding: 10px 0 10px 0;
		border-bottom: 1px solid #EEEEEE;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.icon {
		padding: 0 5px;
		align-self: flex-end;
		color: #aaa;
		margin-right: 5px;
	}

	.unitTable,
	.unitTodo {
		border-bottom: 1px solid #EEEEEE;
		padding: 5px;
		color: #555555;
	}

	.refresh {
		font-size: 15px;
		padding-bottom: 1px;
		padding-right: 4px;
	}

	.setStatus {
		color: #555555;
		border: 1px solid #EEEEEE;
		padding: 7px;
		border-radius: 20px;
	}
</style>
