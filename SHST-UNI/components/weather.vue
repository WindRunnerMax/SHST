<template>
    <view>

        <view class="weather">
            <view class="weather-left">
                <view class="flex-center">
                    <image class="today-img" mode="aspectFit" :src="host+'/public/static/weather/'+todayWeather[1]+'.png'"></image>
                </view>
                <view class="first-line">{{todayWeather[0]}}</view>
                <view class="second-line">{{todayWeather[2]}}℃ - {{todayWeather[3]}}℃</view>
                <view class="second-line">{{todayWeather[4]}}</view>
            </view>
            <view class="weather-right">
                <view class="weather-right-top">
                    <image class="day-img" mode="aspectFit" :src="host+'/public/static/weather/'+tomorrowWeather[1]+'.png'"></image>
                    <view class="weather-con">
                        <view class="first-line">{{tomorrowWeather[0]}}</view>
                        <view class="second-line">{{tomorrowWeather[2]}}℃ - {{tomorrowWeather[3]}}℃</view>
                    </view>
                </view>
                <view class="weather-right-bot">
                    <image class="day-img" mode="aspectFit" :src="host+'/public/static/weather/'+tdatomoWeather[1]+'.png'"></image>
                    <view class="weather-con">
                        <view class="second-line">{{tdatomoWeather[0]}}</view>
                        <view style="text-align:center;">{{tdatomoWeather[2]}}℃ - {{tdatomoWeather[3]}}℃</view>
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
        data: function() {
            return {
                todayWeather: ["", "CLEAR_DAY", 0, 0, "数据获取中"],
                tomorrowWeather: ["", "CLEAR_DAY", 0, 0],
                tdatomoWeather: ["", "CLEAR_DAY", 0, 0],
                host: "https://www.touchczy.top"
            }
        },
        created: function() {
            var ran = ~~(Math.random() * 100000000000);
            uni.request({
                url: "https://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=" +
                    ran,
                success: (res) => {
                    if (res.data.status === "ok") {
                        var weatherData = res.data.result.daily;
                        this.todayWeather = [weatherData.skycon[0].date, weatherData.skycon[0].value, weatherData.temperature[0].min,
                            weatherData.temperature[0].max, res.data.result.hourly.description
                        ]
                        this.tomorrowWeather = [weatherData.skycon[1].date, weatherData.skycon[1].value, weatherData.temperature[1].min,
                            weatherData.temperature[1].max
                        ]
                        this.tdatomoWeather = [weatherData.skycon[2].date, weatherData.skycon[2].value, weatherData.temperature[2].min,
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

    .weather-left {
        width: 50% ;
        padding: 10px;
        border-right: 1px solid #eee;
    }

    .today-img {
        width: 40px !important;
        height: 40px !important;
    }
    
    .flex-center{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .day-img {
        width: 30px !important;
        height: 30px !important;
        margin: 0 0 0 15px;
        align-self: center;
    }

    .weather-right {
        width: 50%;
    }

    .weather-right-bot,
    .weather-right-top {
        display: flex;
        height: 50%;
        text-align: center;
    }

    .weather-right-bot {
        border-top: 1px solid #eee;
    }

    .weather-con {
        align-self: center;
        margin: 0 auto;
    }
    
    .first-line{
        margin-top:6px;
        text-align:center;
    }
    
    .second-line{
        margin-top:6px;
        text-align:center;
    }
</style>
