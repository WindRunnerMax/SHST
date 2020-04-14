<template>
	<view>

		<layout>
			<weather></weather>
		</layout>


		<layout :title="today">
			<view slot="headslot">
				<view class="y-CenterCon">
					<view class='iconfont icon-shuaxin icon refresh' @tap='refresh'></view>
					<button open-type='share' class='iconfont icon-fenxiang icon btn'></button>
				</view>
			</view>
			<view class='articalCon' @tap='articalJump'>
				<i class='iconfont icon-gonggao icon'></i>
				<rich-text class='a-link' :nodes="artical"></rich-text>
			</view>
			<navigator url="/pages/User/announce/announce" open-type="navigate" class='articalCon' hover-class="none">
				<i class='iconfont icon-gonggao icon'></i>
				<text class='a-link'>更多公告...</text>
			</navigator>

		</layout>


		<layout title="今日课程">
			<view v-for="(item,index) in table" :key="index">
				<view class='unitTable' v-show="item">
					<view class="y-CenterCon" style="margin: 5px 0;">
						<view class="a-dot" :style="{'background':item[5],'margin':'0 6px 0 3px'}"></view>
						<view>第{{2*(item[1] + 1) - 1}}{{2*(item[1] + 1)}}节</view>
						<view style="margin-left: 5px;">{{item[3]}}</view>
					</view>
					<view class="y-CenterCon">
						<view style="margin: 3px;">{{item[2]}}</view>
						<view style="margin-left: 5px;">{{item[4]}}</view>
					</view>
				</view>
			</view>
			<view class='unitTable' v-if="tips" @tap='bindSW'>
				<view class="y-CenterCon" style="margin: 5px 0;">
					<view class="a-dot" style='background:#eee;margin: 0 6px 0 3px;'></view>
					<view>{{tips}}</view>
				</view>
				<view style="margin:7px 3px 5px 3px;">{{tipsInfo}}</view>
			</view>
		</layout>

		<!-- #ifndef MP-WEIXIN -->
		<layout title="待办事项">
			<view v-for="(item,index) in todoList" :key="index">
				<view class='y-CenterCon unitTodo' style="justify-content: space-between;">
					<view>
						<view class="y-CenterCon" style="margin: 5px 0;">
							<view class="a-dot" :style="{'background':item.color,'margin':'0 6px 0 3px'}"></view>
							<view>{{item.event_content}}</view>
						</view>
						<view class="y-CenterCon">
							<view style="margin: 3px;">{{item.todo_time}}</view>
							<view style="margin-left: 10px;">{{item.diff}}天</view>
						</view>
					</view>
					<view>
						<i class='iconfont icon-banner setStatus' @tap='setStatus' :data-id="item.id" :data-index="index">
						</i>
					</view>
				</view>
			</view>
			<view class='unitTable' v-if="tips2">
				<view class="y-CenterCon" style="margin: 5px 0;">
					<view class="a-dot" style='background:#eee;margin: 0 6px 0 3px;'></view>
					<view>{{tips2}}</view>
				</view>
				<view style="margin:7px 3px 5px 3px;">快去添加一个想做的事吧</view>
			</view>
		</layout>
		<!-- #endif -->


		<layout title="每日一句">
			<sentence></sentence>
		</layout>

	</view>
</template>

<script>
	import weather from "@/components/weather.vue"
	import sentence from "@/components/sentence.vue"
	const app = getApp()
	const util = require("@/utils/util.js")
	const pubFct = require("@/vector/pubFct.js")

	export default {
		components: {
			weather,
			sentence
		},
		data() {
			return {
				today: util.formatDate("yyyy-MM-dd K"),
				table: [],
				todoList: [],
				tips: "数据加载中",
				tipsInfo: "数据加载中",
				tips2: "数据加载中",
				artical: "数据加载中"
			}
		},
		onLoad: function(options) {
			app.eventBus.on('LoginEvent', this.openidEvent);
			if (app.globalData.openid !== "") this.openidEvent({data: {Message: app.globalData.loginStatus}});
		},
		methods: {
			openidEvent: function(res) {
				console.log("Login EventBus Execute");
				this.loginSatatus(res.data.Message);
				this.getArtical();
				this.getTable();
				// #ifndef MP-WEIXIN
				this.getEvent();
				// #endif
			},
			loginSatatus: function(status) {
				if (status === "Yes") {
					this.tips = "点我前去绑定教务系统账号";
					this.tipsInfo = "绑定强智教务系统就可以使用山科小站咯";
				}
			},
			/**
			 * 课表处理
			 */
			getTable: function() {
				var tableCache = uni.getStorageSync("table") || {};
				if (tableCache.term === app.globalData.curTerm && tableCache.classTable && tableCache.classTable[app.globalData.curWeek]) {
					console.log("GET TABLE FROM CACHE");
					var showTableArr = pubFct.tableDispose(tableCache.classTable[app.globalData.curWeek], 1);
					this.tipsDispose(showTableArr);
				} else {
					this.getRemoteTable();
				}
			},
			getRemoteTable: async function(load = 1) {
				if (app.globalData.userFlag === 1) {
					console.log("GET TABLE FROM REMOTE");
					var res = await app.request({
						load: load,
						url: app.globalData.url + 'sw/table',
						data: {
							week: app.globalData.curWeek,
							term: app.globalData.curTerm
						}
					})
					if (res.data.Message === "Yes") {
						var showTableArr = pubFct.tableDispose(res.data.data, 1);
						this.tipsDispose(showTableArr);
						var tableCache = uni.getStorageSync('table') || {
							term: app.globalData.curTerm,
							classTable: []
						};
						tableCache.term = app.globalData.curTerm;
						tableCache.classTable[app.globalData.curWeek] = res.data.data;
						uni.setStorage({
							key: 'table',
							data: tableCache
						})
					} else {
						app.toast("ERROR");
						this.tips = "加载失败";
						this.tipsInfo = "加载失败了，重新登录试一下";
					}
				}
			},
			tipsDispose: function(info) {
				if (!app.globalData.userFlag) return false;
				this.table = info ? info : [];
				this.tips = info ? "" : "No Class Today";
				this.tipsInfo = info ? "" : "今天没有课，快去自习室学习吧";
			},
			refresh: function(e) {
				uni.setStorageSync('table', {
					term: app.globalData.curTerm,
					classTable: []
				});
				this.getRemoteTable(2);
			},

			// #ifndef MP-WEIXIN
			/**
			 * 待办处理
			 */
			eventDipose: function(data) {
				uni.setStorageSync('event', data);
				if (data.length === 0) {
					this.tips2 = "暂无待办事项";
					return;
				} else {
					this.tips2 = "";
				}
				var curData = util.formatDate();
				data.map(function(value) {
					var diff_color = pubFct.todoDateDiff(curData, value.todo_time, value.event_content);
					value.diff = diff_color[0];
					value.color = diff_color[1];
					return value;
				})
				data.sort((a, b) => {
					return a.todo_time > b.todo_time ? 1 : -1;
				});
				this.todoList = data;
			},
			getEvent: function() {
				var eventCache = uni.getStorageSync('event') || "";
				if (eventCache !== "") {
					console.log("GET EVENT FROM CACHE");
					this.eventDipose(eventCache);
				} else {
					this.getRemoteEvent();
				}
			},
			getRemoteEvent: async function() {
				if (app.globalData.userFlag !== 1) {
					this.tips2 = "暂无待办事项";
					return false;
				}
				console.log("GET EVENT FROM REMOTE");
				var res = await app.request({
					url: app.globalData.url + "todo/getEvent",
				})
				if (res.data.data && res.data.data != 3) this.eventDipose(res.data.data);
				else this.tips2 = "加载失败"
			},
			setStatus: async function(e) {
				var [err,choice] = uni.showModal({
					title: '提示',
					content: '确定标记为已完成吗',
				})
				if (choice.confirm) {
					var index = e.currentTarget.dataset.index;
					var id = e.currentTarget.dataset.id;
					var res = await app.request({
						url: app.globalData.url + "todo/setStatus",
						method: "POST",
						data: {
							id: id
						}
					})
					app.toast("标记成功");
					this.todoList.splice(index, 1);
					uni.setStorageSync('event', this.todoList);
					this.tips2 = this.todoList.length === 0 ? "暂没有待办事项" : "";
				}
			},

			// #endif
			getArtical: function() {
				if (app.globalData.initData && app.globalData.initData.articalName) {
					// #ifdef MP-ALIPAY
					this.artical = [{
						type: "text",
						text: app.globalData.initData.articalName
					}]
					// #endif
					// #ifndef MP-ALIPAY
					this.artical = app.globalData.initData.articalName
					// #endif
				}
			},
			articalJump: function() {
				if (app.globalData.initData && app.globalData.initData.articleUrl) {
					var url = encodeURIComponent(app.globalData.initData.articleUrl);
					// #ifdef MP-WEIXIN
					uni.navigateTo({
						url: '/pages/Home/auxiliary/webview?url=' + url
					})
					// #endif
					// #ifndef MP-WEIXIN
					uni.setClipboardData({
						data: app.globalData.initData.articleUrl
					})
					// #endif
				}
			},
			bindSW: function() {
				if (app.globalData.userFlag === 0) {
					uni.navigateTo({
						url: '/pages/Home/auxiliary/login'
					})
				} else return 0;
			},
			onShareAppMessage: function() {}
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
