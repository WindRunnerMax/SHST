<template>
	<view>

		<layout title="查成绩">
			<view class="selectCon">
				<view>请选择学期</view>
				<picker @change="bindPickerChange" :value="index" :range="yearArr" class="link" range-key="show">
					<view>{{yearArr[index].show}}</view>
				</picker>
			</view>
		</layout>

		<view v-if="show">
			<headslot :title="showSelect">
				<view class="y-CenterCon" style="flex-wrap: wrap; font-size: 13px;">
					<view class='y-CenterCon overUnit'>
						<view class="dot" style="background:#6495ED;"></view>
						<view>学分:{{point}}</view>
					</view>
					<view class='y-CenterCon overUnit'>
						<view class="dot" style="background:#ACA4D5;"></view>
						<view>绩点:{{pointN}}</view>
					</view>
					<view class='y-CenterCon overUnit'>
						<view class="dot" style="background:#EAA78C;"></view>
						<view>加权:{{pointW}}</view>
					</view>
				</view>
			</headslot>
			<view style="margin-top: 10px;"></view>
			<view v-for="(item,index) in grade" :key="index">
				<layout>
					<view class="unit adapt">
						<view class='infoLeft'>
							<view class='cName'>{{item.kcmc}}</view>
							<view style='color:#aaa;'>{{item.kclbmc}}</view>
							<view style='color:#aaa;'>{{item.ksxzmc}}</view>
						</view>
						<view class='infoRight'>
							<view class='cgrade'>{{item.zcj}}</view>
							<view style='color:#aaa;margin-top: 3px;'>{{item.xf}}学分</view>
						</view>
					</view>
				</layout>
			</view>

			<layout v-if="ad">
				<!-- #ifdef MP-WEIXIN -->
				<ad unit-id="adunit-31c347091893cf0c" class="adapt" binderror="adError"></ad>
				<!-- #endif -->
			</layout>

		</view>


	</view>
</template>

<script>
	const app = getApp()
	import headslot from "@/components/headslot.vue"
	export default {
		components: {
			headslot
		},
		data() {
			return {
				index: 1,
				yearArr: [{}, {
					show: "请稍后",
					value: ""
				}],
				point: 0,
				pointN: 0,
				pointW: 0,
				show: 0,
				grade: 0,
				ad: 1,
				showSelect: ""
			}
		},
		onLoad: function(options) {
			// 处理学期
			var year = parseInt(app.globalData.curTerm.split("-")[1]);
			var yearArr = [{
				show: '全部学期',
				value: ""
			}];
			for (var i = 1; i <= 4; ++i) {
				let firstTerm = (year - i) + "-" + (year - i + 1) + "-2";
				let secondTerm = (year - i) + "-" + (year - i + 1) + "-1";
				if (firstTerm <= app.globalData.curTerm) {
					yearArr.push({
						show: firstTerm,
						value: firstTerm
					})
				}
				if (secondTerm <= app.globalData.curTerm) {
					yearArr.push({
						show: secondTerm,
						value: secondTerm
					})
				}
			}
			this.yearArr = yearArr
			this.initGrade();
		},
		methods: {
			bindPickerChange(e) {
				var that = this;
				console.log(this.yearArr[e.detail.value].value);
				var stuYear = this.yearArr[e.detail.value].value;
				var query = (stuYear === "" ? "" : "/" + stuYear);
				this.showSelect = this.yearArr[e.detail.value].show;
				this.index = e.detail.value
				this.getGradeRemote(query);
			},
			initGrade() {
				var that = this;
				var stuYear = this.showSelect = app.globalData.curTerm;
				var query = (stuYear === "" ? "" : "/" + stuYear);
				this.getGradeRemote(query);
			},
			getGradeRemote(query) {
				var that = this;
				app.ajax({
					load: 2,
					url: app.globalData.url + 'funct/sw/grade' + query,
					fun: res => {
						if (res.data.MESSAGE !== "Yes") {
							app.toast("ERROR");
							return;
						}
						if (res.data.data[0]) {
							try {
								var info = res.data.data;
								var point = 0;
								var pointN = 0;
								var pointW = 0;
								var n = 0;
								info.forEach(function(value) {
									if (value.kclbmc !== "公选") {
										n++;
										point += value.xf;
										if (value.zcj === "优") {
											pointN += 4.5;
											pointW += (4.5 * value.xf);
										} else if (value.zcj === "良") {
											pointN += 3.5;
											pointW += (3.5 * value.xf);
										} else if (value.zcj === "中") {
											pointN += 2.5;
											pointW += (2.5 * value.xf);
										} else if (value.zcj === "及格") {
											pointN += 1.5;
											pointW += (1.5 * value.xf);
										} else if (value.zcj === "不及格") {} else {
											var s = parseInt(value.zcj);
											if (s >= 60) {
												pointN += ((s - 50) / 10);
												pointW += (((s - 50) / 10) * value.xf);
											}
										}
									}
								})
								that.point = point
								that.pointN = (pointN / n).toFixed(2)
								that.pointW = (pointW / point).toFixed(2)
							} catch (err) {
								console.log(err);
							}

						}
						let defaultValue = {kclbmc: "暂无",kcmc: this.showSelect+"学期暂无成绩",ksxzmc: "暂无成绩",xf: 0,zcj: "100"}
						that.grade = !res.data.data[0] ? [defaultValue] : res.data.data
						that.ad = !res.data.data[0] ? 0 : 1
						that.show = 1
					}
				})
			},
			adError(e) {
				this.ad = 0
			}
		}
	}
</script>

<style>
	.selectCon {
		display: flex;
		justify-content: space-between;
		padding: 15px 0 7px 0;
	}

	.overUnit {
		margin: 0 3px;
	}

	.dot {
		margin: 0 3px;
	}



	.unit {
		padding: 3px 0 ;
		display: flex;
		justify-content: space-between;
	}

	.cName {
		font-size: 14px;
	}

	.infoRight {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
	}

	.infoLeft {
		display: flex;
		flex-direction: column;
		line-height: 21px;
	}

	.cgrade {
		font-size: 20px;
		color: #569FD1;
	}
</style>
