<template>
	<view>

		<layout title="借阅查询">
			<view v-for="(item,index) in data" :key="index">
				<view class="card">
					<rich-text :nodes="item[0]" class="info strong"></rich-text>
					<rich-text :nodes="item[1]" class="info"></rich-text>
					<rich-text :nodes="item[2]" class="info"></rich-text>
					<rich-text :nodes="item[3]" class="info"></rich-text>
					<rich-text :nodes="item[4]" class="info"></rich-text>
					<rich-text :nodes="item[5]" class="info"></rich-text>
					<rich-text :nodes="item[6]" class="info"></rich-text>
					<rich-text :nodes="item[7]" class="info"></rich-text>
				</view>
				<view class="a-hr"></view>
			</view>
		</layout>


		<layout title="Tips:">
			<view>1.图书馆逾期是不扣钱的 </view>
			<view>2.如果您借了书但出现PARSE ERROR，有可能您修改了图书馆默认密码，或者是图书馆服务器暂时瘫痪</view>
			<view>3.学校图书馆外网访问会定时关闭，正常使用时间大约是在 7:00-22:00</view>
		</layout>

	</view>
</template>

<script>
	const app = getApp();
	const util = require("@/utils/util.js")
	export default {
		data() {
			return {
				data: []
			}
		},
		onLoad: function() {
			var curData = new Date();
			var startTime = "7:00";
			var endTime = "22:30";
			var curTime = curData.getHours() + ":" + curData.getMinutes();
			if (util.compareTimeInSameDay(startTime, curTime) || util.compareTimeInSameDay(curTime, endTime)) {
				app.toast("当前时间图书馆服务已关闭");
				return;
			}
			var that = this;
			app.ajax({
				load: 2,
				url: app.globalData.url + "lib/borrow",
				fun: res => {
					if (res.data.Message === "Yes") {
						var infoArr = [];
						res.data.info.match(/<li>[\s\S]*?<\/li>/g).forEach(value => {
							var infoArrInner = [];
							infoArrInner.push(value.match(/<h3>.*?<\/h3>/)[0].replace(/[<h3>]|[<\/h3>]/g, ""));
							value.match(/<p.*?>[\s\S]*?<\/p>/g).forEach(value2 => {
								infoArrInner.push(value2);
							})
							infoArr.push(infoArrInner);
						})
						// #ifdef MP-ALIPAY
						infoArr = infoArr.map((value) => {
							value = value.map((value2) => { 
								value2 = value2.replace(/<p >/,"");
								value2 = value2.replace(/<p  class="remind">/,"");
								value2 = value2.replace(/<\/p>/,"");
								return [{type:"text",text:value2}];
							});
							return value;
						})
						// #endif
						console.log(infoArr);
						that.data = infoArr
					} else {
						app.toast("响应超时");
					}

				}
			})
		},
		methods: {

		}
	}
</script>

<style>
.card {
  padding: 10px;
  line-height: 27px;
}

.strong {
  font-size: 20px;
  line-height: 27px;
}
</style>
