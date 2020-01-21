<template>
	<view>

		<layout title="空教室">
			<view class='top'>
				<picker-view indicator-style="height: 40px;" style="width: 77%; height: 100px;" @change="bindPickerChange">
					<picker-view-column>
						<view v-for="(item,index) in queryData" :key="index" style="line-height: 40px">{{item[1]}}</view>
					</picker-view-column>
					<picker-view-column>
						<view v-for="(item,index) in queryTime" :key="index" style="line-height: 40px">{{item[0]}}</view>
					</picker-view-column>
					<picker-view-column>
						<view v-for="(item,index) in queryFloor" :key="index" style="line-height: 40px">{{item[0]}}</view>
					</picker-view-column>
				</picker-view>
				<view class='buttonCon'>
					<view class='a-btn search' @tap='loadClassroom'>搜索</view>
				</view>
			</view>
		</layout>

		<layout v-if="show" :title="qShow+'['+searchData+']'">
			<view class='floorName'>{{room[0].jxl}}</view>
			<view class="roomCon">
				<view v-for="(inner,innerIndex) in room[0].jsList" :key="innerIndex">
					<view class='unit'>{{inner.jsmc}}</view>
				</view>
			</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp()
	const util = require("@/utils/util.js")
	export default {
		data() {
			return {
				show: 0,
				room: [],
				qShow: "",
				searchData: util.formatDate(),
				searchTime: '0102',
				searchFloor: 1,
				index: [0, 0, 0],
				queryData: [],
				queryTime: [],
				queryFloor: []
			}
		},
		onLoad: function(options) {
			var queryData = this.getTimeArr();
			var queryTime = [
				['12节', '0102', '12节(8:00-9:50)'],
				['34节', '0304', '34节(10:10-12:00)'],
				['56节', '0506', '56节(14:00-15:50)'],
				['78节', '0708', '78节(16:00-17:50)'],
				['9X节', '0910', '9X节(19:00-20:50)'],
				['上午', 'am', '上午(8:00-12:00)'],
				['下午', 'pm', '下午(14:00-17:50)'],
				['全天', 'allday', '全天(8:00-20:50)']
			];
			var queryFloor = [
				["J1", "1"],
				["J3", "3"],
				["J5", "5"],
				["J7", "7"],
				["J14", "14"],
				["S1", "S1"]
			];
			this.queryData = queryData
			this.queryTime = queryTime
			this.queryFloor = queryFloor
		},
		methods: {
			flagChange(e) {
				var flagIndex = parseInt(e.currentTarget.dataset.index);
				this.data.flag[flagIndex] = this.data.flag[flagIndex] === 'none' ? "flex" : "none";
				this.flag = this.data.flag
			},
			loadClassroom(e) {
				var that = this;
				uni.setNavigationBarTitle({
					title: '加载中...'
				})
				setTimeout(() => that.loadClassroomSetTime(e), 300);
			},
			loadClassroomSetTime(e) {
				var that = this;
				app.ajax({
					load: 2,
					data: {
						searchData: that.searchData,
						searchTime: that.searchTime,
						searchFloor: that.searchFloor,
					},
					url: app.globalData.url + 'sw/classroom',
					fun: res => {
						if (res.data.MESSAGE !== "Yes") {
							app.toast("ERROR");
							return;
						}
						if (res.data.data.flag) {
							app.toast("未生成教学周历");
							return;
						}
						var data = res.data.data;
						if (!data[0]) data = [{
							"jxl": "青岛校区-" + that.searchFloor + "号楼",
							jsList: [{
								jsmc: "无空教室"
							}]
						}];
						data[0].jsList.sort((a, b) => {
							return a.jsmc > b.jsmc ? 1 : -1;
						});
						that.room = data
						that.show = 1
						that.qShow = that.queryTime[that.index[1]][2]
						that.searchData = that.searchData
					}
				})
			},
			getTimeArr() {
				var weekShow = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
				var date = new Date();
				var year = date.getFullYear();
				var queryDataArr = [];
				var week = new Date().getDay();
				console.log(week);
				for (var i = 0; i < 7; ++i) {
					let monthTemp = date.getMonth() + 1;;
					let dayTemp = date.getDate();
					let weekTemp = week + i;
					if (monthTemp < 10) monthTemp = "0" + monthTemp;
					if (dayTemp < 10) dayTemp = "0" + dayTemp;
					queryDataArr.push([year + "-" + monthTemp + "-" + dayTemp, weekShow[weekTemp % 7]]);
					date.addDate(0, 0, 1);
				}
				console.log(queryDataArr);
				return queryDataArr;
			},
			bindPickerChange(e) {
				var that = this;
				this.index = e.detail.value;
				this.searchData = that.queryData[e.detail.value[0]][0];
				this.searchTime = that.queryTime[e.detail.value[1]][1];
				this.searchFloor = that.queryFloor[e.detail.value[2]][1];
			},
			resetInfo() {
				this.searchData = util.formatDate()
				this.searchTime = '0102'
			}
		}
	}
</script>

<style>
	.top {
		display: flex;
		margin: 20px 0;
		text-align: center;
		justify-content: space-between;
	}

	.unit {
		display: flex;
		padding: 10px 7px;
		font-size: 13px;
		background: #eee;
		margin: 3px;
	}
	
	.floorName{
		border-bottom: 1px solid #eee;
		padding: 10px 0 ;
		text-align: center;
		margin: 0 0 8px 0;
	}

	.roomCon {
		display: flex;
		flex-wrap: wrap;
		align-content: center;
		align-items: center;
		justify-content: center;
		justify-items: center;
	}

	.buttonCon {
		margin: 0;
		width: 20%;
		max-width: 78px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		box-sizing: content-box;
	}

	.search {
		height: auto;
		line-height: unset;
		padding: 10px 15px;
		background: #1e9fff;
		color: #fff;
		border-radius: 3px;
		word-break: break-all;
		transition: all 0.3s;
	}
</style>
