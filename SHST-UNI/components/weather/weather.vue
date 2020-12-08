<template>

    <view class="weather">
        <view class="y-center retract">
            <image class="img" mode="aspectFit" :src="host+'/public/static/weather/'+today.skycon+'.png'"></image>
            <view class="lml text">{{today.min}}℃ - {{today.max}}℃</view>
            <view class="text-ellipsis a-flex-full">
                <view class="lml text-ellipsis text" @click="showDes(today.des)">{{today.des}}</view>
            </view>
        </view>
        <view class="hr"></view>
        <view class="y-center img-list-con retract lbl">
            <view v-for="item in 7" :key="item">
                <image class="img" mode="aspectFit" :src="host+'/public/static/weather/'+skycon[(item+1) % 5].value+'.png'"></image>
            </view>
        </view>
    </view>

</template>
<script>
    export default {
        name: "weather",
        props: {},
        methods: {},
        data: () =>({
            today: {skycon: "CLEAR_DAY", min: 0, max: 0, des: "数据获取中"},
            skycon: [{value: "CLEAR_DAY"},{value: "CLEAR_DAY"},{value: "CLEAR_DAY"},{value: "CLEAR_DAY"},{value: "CLEAR_DAY"},],
            host: "https://shst.touchczy.top"
        }),
        created: function() {
            var ran = ~~(Math.random() * 100000000000);
            uni.request({
                url: "https://api.caiyunapp.com/v2/Y2FpeXVuIGFuZHJpb2QgYXBp/120.127164,36.000129/weather?lang=zh_CN&device_id=" +
                    ran,
                success: (res) => {
                    if (res.data.status === "ok") {
                        var weather = res.data.result.daily;
                        this.today = {skycon: weather.skycon[0].value, min: weather.temperature[0].min,
                            max: weather.temperature[0].max, des: res.data.result.hourly.description
                        };
                        this.skycon = weather.skycon;
                    }
                }
            })
        },
        methods: {
            showDes: function(info){
                uni.showToast({title: info, icon: "none"});
            }
        }
    }
</script>
<style scoped>
    .weather{
        border-bottom: 1px solid #eee;
        box-sizing: border-box;
    }
    .retract{
        padding: 0 10px;
    }
    .text{
        color: #555;
    }
    .y-center{
        display: flex;
        align-items: center;
    }
    .img{
        width: 21px !important;
        height: 21px !important;
    }
    .img-list-con{
        display: flex;
        justify-content: space-between;
    }
    .text-ellipsis{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .lml{
       margin-left: 10px;
    }
    .lbl{
        margin-bottom: 7px;
    }
    .hr {
    	display: block;
    	height: 1px;
    	background: #EEEEEE;
    	border: none;
    	margin: 10px 0;
    }
</style>
