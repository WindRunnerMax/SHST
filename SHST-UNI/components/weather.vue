<template name="weather">
	<view>

		<view class='weather'>
			<view class='weaLeft'>
				<view style="display: flex;align-items: center;justify-content: center;">
					<image class='todayImg' mode="widthFix" :src="host+'/public/static/weather/'+todayWeather[1]+'.png'"></image>
				</view>
				<view style='text-align:center;margin-top:6px;'>{{todayWeather[0]}}</view>
				<view style='text-align:center;margin-top:3px;'>{{todayWeather[2]}}℃ - {{todayWeather[3]}}℃</view>
				<view style='text-align:center;margin-top:3px;'>{{todayWeather[4]}}</view>
			</view>
			<view class='weaRight'>
				<view class='weaRightTop'>
					<image class='dayImg' mode="aspectFit" :src="host+'/public/static/weather/'+tomorrowWeather[1]+'.png'"></image>
					<view class='weatherCon'>
						<view style='text-align:center;margin-top:6px;'>{{tomorrowWeather[0]}}</view>
						<view style='text-align:center;margin-top:3px;'>{{tomorrowWeather[2]}}℃ - {{tomorrowWeather[3]}}℃</view>
					</view>
				</view>
				<view class='weaRightBot'>
					<image class='dayImg' mode="aspectFit" :src="host+'/public/static/weather/'+tdatomoWeather[1]+'.png'"></image>
					<view class='weatherCon'>
						<view style='text-align:center;margin-top:3px;'>{{tdatomoWeather[0]}}</view>
						<view style='text-align:center;'>{{tdatomoWeather[2]}}℃ - {{tdatomoWeather[3]}}℃</view>
					</view>
				</view>
			</view>
		</view>

	</view>
</template>
<script>
	export default {
		name: "weather",
		props: {},
		methods: {},
		data() {
			return {
				todayWeather: ["", "CLEAR_DAY", 0, 0, "数据获取中"],
				tomorrowWeather: ["", "CLEAR_DAY", 0, 0],
				tdatomoWeather: ["", "CLEAR_DAY", 0, 0],
				host: "https://www.touchczy.top"
			}
		},
		created: function() {
			var that = this;
			var ran = parseInt(Math.random() * 100000000000);
			uni.request({
				url: "https://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=" +
					ran,
				success: function(res) {
					if (res.data.status === "ok") {
						var weatherData = res.data.result.daily;
						that.todayWeather = [weatherData.skycon[0].date, weatherData.skycon[0].value, weatherData.temperature[0].min,
							weatherData.temperature[0].max, res.data.result.hourly.description
						]
						that.tomorrowWeather = [weatherData.skycon[1].date, weatherData.skycon[1].value, weatherData.temperature[1].min,
							weatherData.temperature[1].max
						]
						that.tdatomoWeather = [weatherData.skycon[2].date, weatherData.skycon[2].value, weatherData.temperature[2].min,
							weatherData.temperature[2].max
						]
					}
				}
			})
		}
	}
</script>
<style>
	.weather {
		display: flex;
		border: 1px solid #eee;
		transition: all 0.8s;
		font-size: 13px;
		border-radius: 3px;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}

	.weaLeft {
		width: 50% ;
		padding: 10px;
		border-right: 1px solid #eee;
	}

	.todayImg {
		width: 40px !important;
		height: 40px !important;
	}

	.dayImg {
		width: 30px !important;
		height: 30px !important;
		margin: 0 0 0 15px;
		align-self: center;
	}

	.weaRight {
		width: 50%;
	}

	.weaRightBot,
	.weaRightTop {
		display: flex;
		height: 50%;
		text-align: center;
	}

	.weaRightBot {
		border-top: 1px solid #eee;
	}

	.weatherCon {
		align-self: center;
		margin: 0 auto;
	}
</style>
