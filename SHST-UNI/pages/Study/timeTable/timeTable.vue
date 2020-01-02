<template>
	<view>

		<layout title="查课表">
			<view class='tableTop'>
				<view class='week'>第{{week}}周</view>
				<view style='display:flex;'>
					<view class='asse-btn asse-btn-white asse-btn-mini refresh' :data-week='week' @tap='refresh'>
						<view class='operate iconfont icon-shuaxin1'></view>
					</view>
					<view class='asse-btn asse-btn-white asse-btn-mini pre' style='font-size: 14px;' :data-week='week' @tap='pre'>
						<view class='operate'>{{preT}}</view>
					</view>
					<view class='asse-btn asse-btn-white asse-btn-mini next' style='font-size: 14px;' :data-week='week' @tap='next'>
						<view class='operate'>{{nextT}}</view>
					</view>
				</view>
			</view>
			<view class="asse-hr timetablehr"></view>
			<view class='line'>
				<view class='weekUnit'>
					<view>Mon</view>
					<view :class="date[0].s">{{date[0].d}}</view>
				</view>
				<view class='weekUnit'>
					<view>Tue</view>
					<view :class="date[1].s">{{date[1].d}}</view>
				</view>
				<view class='weekUnit'>
					<view>Wed</view>
					<view :class="date[2].s">{{date[2].d}}</view>
				</view>
				<view class='weekUnit'>
					<view>Thur</view>
					<view :class="date[3].s">{{date[3].d}}</view>
				</view>
				<view class='weekUnit'>
					<view>Fri</view>
					<view :class="date[4].s">{{date[4].d}}</view>
				</view>
				<view class='weekUnit'>
					<view>Sat</view>
					<view :class="date[5].s">{{date[5].d}}</view>
				</view>
				<view class='weekUnit'>
					<view>Sun</view>
					<view :class="date[6].s">{{date[6].d}}</view>
				</view>
			</view>
			<view class="asse-hr timetablehr"></view>
			<view v-for="(item,index) in [0,1,2,3,4]" :key="index">
				<view class='line'>
					<view v-for="(inner,innerIndex) in [0,1,2,3,4,5,6]" :key="innerIndex" style="width: 100%;">
						<view v-if="table[inner] && table[inner][item]" class='timetableHide' :style="{'background':table[inner][item][5]}">
							<view>{{table[inner][item][2]}}</view>
							<view>{{table[inner][item][4]}}</view>
							<view>{{table[inner][item][3]}}</view>
						</view>
						<view v-else class='timetableHide'></view>
					</view>
				</view>
				<view class="asse-hr timetablehr"></view>
			</view>
		</layout>
		
		<layout v-if="ad">
			<!-- #ifdef MP-WEIXIN -->
			<ad unit-id="adunit-ce81890e6ff0b2a7" class="adapt" @error="adError"></ad>
			<!-- #endif -->
			<!-- #ifdef MP-QQ -->
			<ad unit-id="98766bd6a7f4cc14e978058a3a365551" class="adapt" @error="adError"></ad>
			<!-- #endif -->
		</layout>

	</view>
</template>

<script>
	const app = getApp()
	// const util = require("@/utils/util.js")
	const pubFct = require("@/vector/pubFct.js")
	export default {
		data() {
			return {
				nextT: '>',
				preT: '<',
				week: 1,
				ad: 1,
				date: [{
					d: "00/00",
					s: "none"
				}, {
					d: "00/00",
					s: "none"
				}, {
					d: "00/00",
					s: "none"
				}, {
					d: "00/00",
					s: "none"
				}, {
					d: "00/00",
					s: "none"
				}, {
					d: "00/00",
					s: "none"
				}, {
					d: "00/00",
					s: "none"
				}],
				table: [
					[]
				]
			}
		},
		onLoad(e) {
			this.week = app.globalData.curWeek;
			this.getCache(app.globalData.curWeek);
		},
		methods: {
			getCache: function(e) {
				var tableCache = uni.getStorageSync("table") || {};
				if (tableCache.term === app.globalData.curTerm && tableCache.classTable && tableCache.classTable[e]) {
					console.log("GET TABLE FROM CACHE");
					var showTableArr = pubFct.tableDispose(tableCache.classTable[e]);
					this.table = showTableArr;
					this.week = e;
					this.getDate();
				} else {
					this.getRemoteTable(e);
				}
			},
			getRemoteTable: function(e) {
				var that = this;
				var urlTemp = "";
				if (typeof(e) === "number") urlTemp += ("/" + e);
				var that = this;
				app.ajax({
					load: 2,
					url: app.globalData.url + 'sw/table' + urlTemp,
					data: {
						week: app.globalData.curWeek,
						term: app.globalData.curTerm
					},
					fun: function(res) {
						if (res.data.Message === "Yes") {
							console.log("GET TABLE FROM REMOTE WEEK " + e);
							var showTableArr = pubFct.tableDispose(res.data.data);
							that.table = showTableArr
							that.week = res.data.week
							var tableCache = uni.getStorageSync('table') || {
								term: app.globalData.curTerm,
								classTable: []
							};
							tableCache.term = app.globalData.curTerm;
							tableCache.classTable[e] = res.data.data;
							uni.setStorage({
								key: 'table',
								data: tableCache
							})
							that.getDate();
						} else {
							app.toast("数据拉取失败");
						}
					}
				})
			},
			pre(e) {
				if (e.target.dataset.week <= 1) return;
				var week = parseInt(e.currentTarget.dataset.week) - 1;
				this.getCache(week);
			},
			next(e) {
				var week = parseInt(e.currentTarget.dataset.week) + 1;
				this.getCache(week);
			},
			adError(e) {
				this.ad = 0
			},
			refresh(e) {
				uni.setStorageSync('table', {term: app.globalData.curTerm,classTable: []});
				var week = parseInt(e.currentTarget.dataset.week);
				this.getRemoteTable(week);
			},
			getDate(e) {
				var week = this.week;
				var curWeekDate = new Date(app.globalData.curTermStart);
				curWeekDate.addDate(0, 0, week * 7 - 8);
				console.log(week, curWeekDate);
				var dataObject = [];
				for (let i = 0; i < 7; ++i) {
					curWeekDate.addDate(0, 0, 1);
					var month = curWeekDate.getMonth() + 1;
					var day = curWeekDate.getDate();
					if (month < 10) month = "0" + month;
					if (day < 10) day = "0" + day;
					let today = new Date();
					dataObject.push({
						d: month + "/" + day,
						s: curWeekDate.getDay() === today.getDay() ? "todayLine" : "none"
					});
				}
				this.date = dataObject
			}
		}
	}
</script>

<style>
	.tableTop {
		display: flex;
		padding: 5px;
		justify-content: space-between;
		height: 30px;
	}

	.week {
		align-self: center;
		margin-left: 10px;
	}

	.pre,
	.next,
	.refresh {
		height: 100%;
		margin-left: 10px;
		align-self: center;
		display: flex;
		justify-content: center;
		align-items: center;
		width: 30px;
	}


	.line {
		display: flex;
	}

	.timetableHide {
		min-height: 130px;
		margin: 0 1.5px;
		text-align: center;
		word-break: break-all;
		color: #fff;
		padding: 1px;
		background: #fff;
		font-size: 13px;
	}

	.timetablehr {
		margin: 3px 0;
		background-color: #eee !important;
		height: 1px;
		border: none;
	}

	.weekUnit {
		text-align: center;
		padding: 5px 0 1px 0;
		font-size: 10px;
		width: 100%;
	}
	
	.weekUnit view{
		padding: 3px 0;
	}

	.adAdapt {
		box-sizing: border-box;
		padding: 5px;
	}

	.operate {
		align-self: center;
	}

	.none {
		border: none;
		font-size: 8px;
	}

	.todayLine {
		font-size: 8px;
		border-bottom: 3px solid #eee;
	}
</style>
