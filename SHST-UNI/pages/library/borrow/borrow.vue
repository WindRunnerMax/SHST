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
            <view class="tips-con">
                <view>1.图书馆逾期是不扣钱的 </view>
                <view>2.如果您借了书但出现ERROR，有可能您修改了图书馆默认密码，或者是图书馆服务器暂时瘫痪</view>
                <view>3.学校图书馆外网访问会定时关闭，正常使用时间大约是在 7:00-22:00</view>
            </view>
        </layout>

    </view>
</template>

<script>
    import {formatDate} from "@/modules/datetime";
    import {regMatch} from "@/modules/regex";
    export default {
        data: () => ({
            data: []
        }),
        onLoad: async function() {
            var startTime = "07:00";
            var endTime = "22:30";
            var curTime = formatDate("hh:mm");
            if (startTime > curTime || curTime > endTime) {
                uni.$app.toast("当前时间图书馆服务已关闭");
                return void 0;
            }
            var res = await uni.$app.request({
                load: 2,
                throttle: true,
                url: uni.$app.data.url + "/lib/borrow",
            })
            if (res.data.info.match(/当前无借阅记录/)) {
                uni.$app.toast("暂无借阅记录");
                return true;
            }
            var infoArr = [];
            var tagList =regMatch(/<li>[\s\S]*?<\/li>/g, res.data.info);
            if(!tagList) {uni.$app.toast("图书馆服务器似乎出现了一些问题"); return false;}
            tagList.forEach(value => {
                var infoArrInner = [];
                infoArrInner.push(regMatch(/&nbsp;(.*?)<\/h3>/g,value)[0]);
                regMatch(/<p.*?>(.*?)<\/p>/,value).forEach(value2 => infoArrInner.push(value2));
                infoArr.push(infoArrInner);
            })
            console.log(infoArr);
            this.data = infoArr;
        },
        methods: {

        }
    }
</script>

<style scoped>
    
    .card,.strong{
        line-height: 27px;
    }
    
    .card {
        padding: 10px;
    }

    .strong {
        font-size: 20px;
    }
   
</style>
