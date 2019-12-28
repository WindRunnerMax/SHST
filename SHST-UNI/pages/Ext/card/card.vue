<template>
	<view>

		<layout title="校园卡查询">
			<view class='supple'>
				<view class='info'>
					<view>姓名</view>
					<rich-text :nodes="name"></rich-text>
				</view>
				<view class='info'>
					<view>卡号</view>
					<rich-text :nodes="account"></rich-text>
				</view>
				<view class='info'>
					<view>卡余额</view>
					<rich-text :nodes="banlance"></rich-text>
				</view>
				<view class='info'>
					<view>过渡余额</view>
					<rich-text :nodes="balanceTemp"></rich-text>
				</view>
			</view>
		</layout>
		<layout>
			<view class="x-CenterCon">
				<view class='asse-btn asse-btn-blue asse-btn-small' @tap='todayQuery'>当日流水查询</view>
				<view class='asse-btn asse-btn-blue asse-btn-small btn' @tap='historyQuery'>历史流水查询</view>
			</view>
		</layout>
		<layout v-if="show">
			<view>
				<view style='border-left:1px solid #eee;border-top:1px solid #eee;width: 100%;'>
					<view class='line'>
						<view class='unit'>时间</view>
						<view class='unit'>类型</view>
						<view class='unit'>商户</view>
						<view class='unit'>交易额</view>
						<view class='unit'>余额</view>
						<view class='unit'>流水号</view>
					</view>
					<view v-for="(item,index) in data" :key="index" class='line'>
						<view class='unit'>{{item.time}}</view>
						<view class='unit'>{{item.status}}</view>
						<view class='unit'>{{item.location}}</view>
						<view class='unit'>{{item.money}}</view>
						<view class='unit'>{{item.balance}}</view>
						<view class='unit'>{{item.serno}}</view>
					</view>
				</view>
			</view>
		</layout>
		<layout v-if="show" title="Tips">
			<view class="tipsCon">
				<view>1. 历史消费记录只显示一个月内消费的前17条记录</view>
				<view>2. 仅作参考，具体数据请于行政楼查阅</view>
			</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp();
	var cardLoad = true;
	export default {
		data() {
			return {
				name: "",
				account: "",
				banlance: "",
				balanceTemp: "",
				data: "",
				show: 0
			}
		},
		onLoad: function(options) {
			var that = this;
			app.ajax({
				load: 2,
				url: app.globalData.url + "card/userInfo",
				success: res => {
					if (res.data.Message === "Yes") {
						cardLoad = false;
						var info = res.data.info;
						var pregInfo = info.match(/<div align="left">[\S]*<\/div>/g);
						var balanceInfo = info.match(/<td class="neiwen">[\S]*<\/td>/g);
						var balance = balanceInfo[0].split("（")[0];
						var balanceTemp = balanceInfo[0].split("）")[1].split("(")[0];
						that.name = pregInfo[0]
						that.account = pregInfo[3]
						that.banlance = balance,
							that.balanceTemp = balanceTemp
					} else if (res.data.Message === "No") {
						app.toast(res.data.info);
					} else {
						app.toast("未知错误");
					}
				}
			})
		},
		methods: {
			todayQuery() {
				var that = this;
				if (cardLoad) {
					app.toast("请稍后");
					return;
				}
				app.ajax({
					load: 2,
					url: app.globalData.url + "card/today",
					success: res => {
						if (res.data.Message === "Yes") {
							that.diposeCardData(res.data.info);
						} else if (res.data.Message === "No") {
							app.toast(res.data.info);
						} else {
							app.toast("未知错误");
						}
					}
				})
			},
			historyQuery() {
				var that = this;
				if (cardLoad) {
					app.toast("请稍后");
					return;
				}
				app.ajax({
					load: 2,
					url: app.globalData.url + "card/history",
					success: res => {
						if (res.data.Message === "Yes") {
							that.diposeCardData(res.data.info);
						} else if (res.data.Message === "No") {
							app.toast(res.data.info);
						} else {
							app.toast("未知错误");
						}
					}
				})
			},
			diposeCardData(data) {
				var line = [];
				var lineData = data.match(/<tr class="listbg[2]?">[\s\S]*?<\/tr>/g);
				if (!lineData) {
					app.toast("暂无数据");
				} else {
					lineData.forEach((value) => {
						var infoArr = value.match(/<td[\s\S]*?>[\s\S]*?<\/td>/g);
						var infoObj = {};
						infoObj.time = infoArr[0].replace(/<[\S]?td[\s\S]*?>/g, "").substring(5, 16);
						infoObj.status = infoArr[3].replace(/<[\S]?td[\s\S]*?>/g, "");
						infoObj.location = infoArr[4].replace(/<[\S]?td[\s\S]*?>/g, "");
						infoObj.money = infoArr[5].replace(/<[\S]?td[\s\S]*?>/g, "");
						infoObj.balance = infoArr[6].replace(/<[\S]?td[\s\S]*?>/g, "");
						infoObj.serno = infoArr[7].replace(/<[\S]?td[\s\S]*?>/g, "");
						line.push(infoObj);
					})
					console.log(line);
				}
				this.data = line
				this.show = 1
			}
		}
	}
</script>

<style>
	.supple {
		box-sizing: border-box;
		color: rgb(134, 134, 134);
		width: 100%;
	}

	.info {
		padding: 5px 0;
		display: flex;
		justify-content: space-between;
	}

	.btnCon {
		display: flex;
		justify-content: center;
	}

	.btn {
		margin-left: 10px;
	}

	.line {
		display: flex;
	}

	.unit {
		box-sizing: border-box;
		width: 30%;
		padding: 15px 5px;
		text-align: center;
		border-bottom: 1px solid #eee;
		border-right: 1px solid #eee;
		display: flex;
		justify-content: center;
		align-items: center;
		word-break: break-all;
	}
</style>
