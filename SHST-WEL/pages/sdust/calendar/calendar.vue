<template>
	<view>

		<view>
			<layout title="校历查询">
				<view class="selectCon">
					<view>请选择学期</view>
					<picker @change="bindPickerChange" :value="index" :range="range" class="a-link">
						<view class="uni-input">{{range[index]}}</view>
					</picker>
				</view>
			</layout>
		</view>


		<view v-if="show">
			<layout :title="term">
				<view class='weekGroup'>
					<view class='weekUnit'>Week</view>
					<view class='weekUnit'>Mon</view>
					<view class='weekUnit'>Tue</view>
					<view class='weekUnit'>Wed</view>
					<view class='weekUnit'>Thur</view>
					<view class='weekUnit'>Fri</view>
					<view class='weekUnit'>Sat</view>
					<view class='weekUnit'>Sun</view>
				</view>
				<view class="timetablehr"></view>
				<view v-for="(item,index) in calendarObj" :key="index" class='con'>
					<view class='line'>
						<view v-for="(inner,innerindex) in item" :key="innerindex" style='width:100%;'>
							<view :class='inner.className'>{{inner.day}}</view>
						</view>
					</view>
				</view>
			</layout>
		</view>

	</view>
</template>

<script>
	import layout from "@/components/layout.vue"
	export default {
		components: {
			layout
		},
		data() {
			return {
				range: ["请稍后"],
				index: 0,
				show: 0,
				term: "",
				calendarObj: []
			}
		},
		onLoad() {
			var that = this
			uni.request({
				url: "https://shst.touchczy.top/ext/calendar",
				header: {
					'content-type': 'application/x-www-form-urlencoded'
				},
				success: (res) => {
					res.data.info = res.data.info.reverse();
					that.data = res.data.info
					var range = [];
					res.data.info.forEach((value) => {
						range.push(value.term);
					})
					that.range = range;
					that.bindPickerChange({detail:{value:0}})
				}
			})
		},
		methods: {
			getNowFormatDate: (yearAdd = 0, monthAdd = 0, dayAdd = 0) => {
				var date = new Date();
				date.addDate(yearAdd, monthAdd, dayAdd);
				var year = date.getFullYear();
				var month = date.getMonth() + 1;
				var day = date.getDate();
				if (month < 10) month = "0" + month;
				if (day < 10) day = "0" + day;
				return year + "-" + month + "-" + day;
			},
			getFormateDateAcceptDate: (date) => {
				var year = date.getFullYear();
				var month = date.getMonth() + 1;
				var day = date.getDate();
				if (month < 10) month = "0" + month;
				if (day < 10) day = "0" + day;
				return year + "-" + month + "-" + day;
			},
			bindPickerChange: function(e) {
				this.index = e.detail.value
				var curObj = this.data[this.index]
				this.term = curObj.term
				var weekCount = curObj.weekcount;
				var startDate = curObj.startdata;
				var vacationWeekStart = curObj.vacationstart;
				var startDateObj = new Date(startDate);
				var curDateObj = this.getNowFormatDate();
				console.log(startDateObj.getDay());
				var calendarObj = [];
				var monthFlag = 0;
				for (var i = 1; i <= weekCount; ++i) {
					var lineCalen = [];
					lineCalen.push({
						"className": "week",
						"day": i.toString()
					})
					for (var k = 1; k <= 7; ++k) {
						var unitCalen = {
							className: "m1"
						};
						var calcMonth = (startDateObj.getMonth() + 1);
						if (calcMonth % 2 === 0) unitCalen.className = "m2";
						if (calcMonth !== monthFlag) {
							unitCalen.day = calcMonth + "月";
							unitCalen.className += " strong";
							monthFlag = calcMonth;
						} else unitCalen.day = startDateObj.getDate().toString();
						if (k === 6 || k === 7 || i >= vacationWeekStart) unitCalen.className += " vacation";
						if (curDateObj === this.getFormateDateAcceptDate(startDateObj)) unitCalen.className += " today";
						lineCalen.push(unitCalen);
						startDateObj.addDate(0, 0, 1);
					}
					calendarObj.push(lineCalen);
				}
				this.calendarObj = calendarObj
				this.show = 1
				console.log(calendarObj);
			}
		}
	}
</script>

<style>
	.selectCon {
		display: flex;
		justify-content: space-between;
		padding: 10px 0 5px 0;
	}


	.m2,
	.m1,
	.week {
		text-align: center;
		padding: 15px 0;
	}

	.today {
		border-radius: 15px;
		color: #fff !important;
		background: #79B2F9;
	}

	.vacation {
		color: red;
	}

	.weekGroup {
		display: flex;
		font-size: 13px;
	}

	.weekUnit {
		text-align: center;
		padding: 5px 0;
		font-size: 12px;
		width: 100%;
	}

	.timetablehr {
		margin: 3px 0;
	}

	.strong {
		color: rgb(0, 115, 255);
	}
	
	.line{
		display: flex;
	}
	
	.week{
	  color: rgb(12, 114, 240);
	}
	.uni-input {
		color: #4C98F7;
		text-decoration: underline;
	}
</style>
